import * as THREE from "three";
import { GLTFLoader }      from "three/examples/jsm/loaders/GLTFLoader.js";
import { RoomEnvironment } from "three/examples/jsm/environments/RoomEnvironment.js";
import { shatter }         from "./shatter.js";

const container = document.getElementById("scene-container");
if (!container) throw new Error("No #scene-container found");

const renderer = new THREE.WebGLRenderer({
  antialias: devicePixelRatio < 2,
  alpha: true,
  powerPreference: "high-performance",
});
renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.4;
renderer.outputColorSpace = THREE.SRGBColorSpace;
container.appendChild(renderer.domElement);

const scene  = new THREE.Scene();
const mouse  = new THREE.Vector2();

const camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
camera.position.set(0, 1.2, 7);
camera.lookAt(0, 0, 0);

function resize() {
  const w = container.clientWidth  || window.innerWidth;
  const h = container.clientHeight || window.innerHeight;
  camera.aspect = w / h;
  camera.updateProjectionMatrix();
  renderer.setSize(w, h);
}
resize();
new ResizeObserver(resize).observe(container);

const pmrem = new THREE.PMREMGenerator(renderer);
pmrem.compileEquirectangularShader();
scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
pmrem.dispose();

scene.add(new THREE.AmbientLight(0xffffff, 0.2));

const keyLight = new THREE.DirectionalLight(0xd0e0ff, 3.5);
keyLight.position.set(-3, 6, 4);
scene.add(keyLight);

const redFill = new THREE.PointLight(0xa7001e, 6, 12);
redFill.position.set(3, -1, 3);
scene.add(redFill);

const rimLight = new THREE.DirectionalLight(0x8888cc, 1.8);
rimLight.position.set(0, -3, -5);
scene.add(rimLight);

const chromeMat = new THREE.MeshPhysicalMaterial({
  color:                    0xc8d4e0,
  metalness:                1.0,
  roughness:                0.06,
  envMapIntensity:          4.0,
  clearcoat:                1.0,
  clearcoatRoughness:       0.0,
  reflectivity:             1.0,
  iridescence:              0.15,
  iridescenceIOR:           1.9,
  iridescenceThicknessRange:[60, 300],
});

const ENTRY = {
  position: new THREE.Vector3(0, 4.5, 2.8),
  rotation: new THREE.Euler(-0.9, 0, 0),
};
const OUT = {
  position: new THREE.Vector3(0, 0.2, 2.2),
  rotation: new THREE.Euler(-0.18, 0.15, 0.04),
};

let vinylModel    = null;
let animState     = "in";
let rotationSpeed = 0.18;
let followAmount  = 1;
let needsRender   = true;

function setVinylCoords(coords) {
  if (!vinylModel) return;
  vinylModel.position.copy(coords.position);
  vinylModel.rotation.copy(coords.rotation);
}

function animateToCoords(target, speed = 0.05) {
  if (!vinylModel) return;
  vinylModel.position.lerp(target.position, speed);
  vinylModel.rotation.x = THREE.MathUtils.lerp(vinylModel.rotation.x, target.rotation.x, speed);
  vinylModel.rotation.y = THREE.MathUtils.lerp(vinylModel.rotation.y, target.rotation.y, speed);
  vinylModel.rotation.z = THREE.MathUtils.lerp(vinylModel.rotation.z, target.rotation.z, speed);
  if (Math.abs(vinylModel.rotation.x - target.rotation.x) < 1e-4) vinylModel.rotation.x = target.rotation.x;
  if (Math.abs(vinylModel.rotation.y - target.rotation.y) < 1e-4) vinylModel.rotation.y = target.rotation.y;
  if (Math.abs(vinylModel.rotation.z - target.rotation.z) < 1e-4) vinylModel.rotation.z = target.rotation.z;
}

new GLTFLoader().load(
  "/3D/vinyl.glb",
  (gltf) => {
    vinylModel = gltf.scene;
    vinylModel.scale.setScalar(0.30);

    const center = new THREE.Box3().setFromObject(vinylModel).getCenter(new THREE.Vector3());
    vinylModel.position.sub(center);

    vinylModel.traverse((c) => {
      if (c.isMesh) {
        c.material = chromeMat;
        c.castShadow = false;
        c.receiveShadow = false;
      }
    });

    setVinylCoords(ENTRY);
    scene.add(vinylModel);
    needsRender = true;

    const loader = document.getElementById("vinyl-loader");
    if (loader) {
      loader.style.display = "none";
      requestAnimationFrame(() => requestAnimationFrame(() => shatter()));
    }
  },
  undefined,
  (err) => console.warn("vinyl.glb load error:", err)
);

function animate() {
  requestAnimationFrame(animate);
  if (document.hidden) return;

  if (!vinylModel) {
    renderer.render(scene, camera);
    return;
  }

  switch (animState) {
    case "in":
      animateToCoords(OUT, 0.09);
      vinylModel.rotation.y += rotationSpeed;
      rotationSpeed = Math.max(rotationSpeed * 0.96, 0.018);
      needsRender = true;
      if (
        vinylModel.position.distanceTo(OUT.position) < 0.01 &&
        Math.abs(vinylModel.rotation.x - OUT.rotation.x) < 0.01
      ) {
        animState = "idle";
        followAmount = 1;
      }
      break;

    case "idle": {
      const t = performance.now() * 0.001;
      const targetX = OUT.rotation.x + mouse.y * 0.12;
      const targetZ = OUT.rotation.z + mouse.x * 0.12;
      followAmount = Math.max(0, Math.min(1, followAmount));
      vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.04 * followAmount;
      vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.04 * followAmount;
      vinylModel.rotation.y += 0.004;
      vinylModel.position.y = OUT.position.y + Math.sin(t * 0.7) * 0.08;
      needsRender = true;
      break;
    }

    case "out":
      followAmount = Math.max(0, followAmount - 0.07);
      animateToCoords(ENTRY, 0.05);
      {
        const targetX = OUT.rotation.x + mouse.y * 0.3;
        const targetZ = OUT.rotation.z + mouse.x * 0.3;
        vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.06 * followAmount;
        vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.06 * followAmount;
      }
      needsRender = true;
      if (
        vinylModel.position.distanceTo(ENTRY.position) < 0.01 &&
        Math.abs(vinylModel.rotation.x - ENTRY.rotation.x) < 0.01 &&
        Math.abs(vinylModel.rotation.y - ENTRY.rotation.y) < 0.01 &&
        Math.abs(vinylModel.rotation.z - ENTRY.rotation.z) < 0.01
      ) {
        setVinylCoords(ENTRY);
        followAmount = 0;
      }
      break;
  }

  if (needsRender) {
    renderer.render(scene, camera);
    needsRender = false;
  }
}
animate();

let mouseDirty = false;
window.addEventListener("mousemove", (e) => {
  if (!mouseDirty) {
    mouseDirty = true;
    requestAnimationFrame(() => {
      mouse.x =  (e.clientX / window.innerWidth)  * 2 - 1;
      mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
      needsRender = true;
      mouseDirty = false;
    });
  }
});

window.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && animState === "idle") animState = "out";
});
