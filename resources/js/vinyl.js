import * as THREE from "three";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";

import { RGBELoader } from "three/examples/jsm/loaders/RGBELoader.js";
import { CubeTextureLoader } from "three";

const dev = false;
const container = document.getElementById("scene-container");

if (!dev && container) {
  const scene = new THREE.Scene();
  const mouse = new THREE.Vector2();
  const camera = new THREE.PerspectiveCamera(
    45,
    window.innerWidth / window.innerHeight,
    0.1,
    100
  );
  camera.position.set(0, 3, 8);

  const renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: true,
    powerPreference: "high-performance",
  });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  if (container) container.appendChild(renderer.domElement);

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.enablePan = false;

  scene.add(new THREE.AmbientLight(0xffffff, 1.5));
  const dirLight = new THREE.DirectionalLight(0xffffff, 2);
  dirLight.position.set(5, 10, 7);
  scene.add(dirLight);

  const ENTRY = {
    position: new THREE.Vector3(0, 0.5, 8),
    rotation: new THREE.Euler(0.2, 0, 0),
  };
  const OUT = {
    position: new THREE.Vector3(0, 1, 0.5),
    rotation: new THREE.Euler(1.2, 0.5, 1),
  };

  let vinylModel;
  let animState = "in";
  let rotationSpeed = 0.14;
  let time = 0;
  let followAmount = 1;


  function setVinylCoords(coords) {
    if (!vinylModel) return;
    vinylModel.position.copy(coords.position);
    vinylModel.rotation.copy(coords.rotation);
  }


  function animateToCoords(target, speed = 0.05) {
    if (!vinylModel) return;
    vinylModel.position.lerp(target.position, speed);
    // Clamp rotation to avoid floating point drift
    vinylModel.rotation.x = THREE.MathUtils.lerp(vinylModel.rotation.x, target.rotation.x, speed);
    vinylModel.rotation.y = THREE.MathUtils.lerp(vinylModel.rotation.y, target.rotation.y, speed);
    vinylModel.rotation.z = THREE.MathUtils.lerp(vinylModel.rotation.z, target.rotation.z, speed);
    // Snap if very close
    if (Math.abs(vinylModel.rotation.x - target.rotation.x) < 1e-4) vinylModel.rotation.x = target.rotation.x;
    if (Math.abs(vinylModel.rotation.y - target.rotation.y) < 1e-4) vinylModel.rotation.y = target.rotation.y;
    if (Math.abs(vinylModel.rotation.z - target.rotation.z) < 1e-4) vinylModel.rotation.z = target.rotation.z;
  }


  // Utilise une HDRI libre de droits pour l'environnement chrome
  new RGBELoader().load(
    "https://dl.polyhaven.org/file/ph-assets/HDRIs/hdr/1k/studio_small_08_1k.hdr",
    (hdrMap) => {
      hdrMap.mapping = THREE.EquirectangularReflectionMapping;
      scene.environment = hdrMap;

      const loader = new GLTFLoader();
      loader.load("/3D/vinyl.glb", (gltf) => {
        vinylModel = gltf.scene;
        vinylModel.scale.setScalar(0.6);

        const box = new THREE.Box3().setFromObject(vinylModel);
        const center = box.getCenter(new THREE.Vector3());
        vinylModel.position.sub(center);

        setVinylCoords(ENTRY);

        // Chrome réaliste avec HDRI
        const chromeMat = new THREE.MeshPhysicalMaterial({
          color: 0xffffff,
          metalness: 1.0,
          roughness: 0.03,
          envMap: hdrMap,
          envMapIntensity: 1.2,
          reflectivity: 1.0,
          clearcoat: 1.0,
          clearcoatRoughness: 0.01
        });

        vinylModel.traverse((child) => {
          if (child.isMesh) {
            child.material = chromeMat;
            child.castShadow = false;
            child.receiveShadow = false;
          }
        });
        scene.add(vinylModel);
      });
    }
  );




  // Animation optimisée pour de meilleures performances
  let lastRender = 0;
  let lastWidth = window.innerWidth;
  let lastHeight = window.innerHeight;
  let controlsNeedsUpdate = true;

  function animate(now) {
    requestAnimationFrame(animate);

    // Limite le framerate à ~30fps si la machine rame
    if (now && lastRender && now - lastRender < 33) return;
    lastRender = now;

    // Met à jour les contrôles moins souvent
    if (controlsNeedsUpdate) {
      controls.update();
      controlsNeedsUpdate = false;
    }

    // Redimensionne le renderer uniquement si la taille a changé
    if (window.innerWidth !== lastWidth || window.innerHeight !== lastHeight) {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
      lastWidth = window.innerWidth;
      lastHeight = window.innerHeight;
    }

    if (!vinylModel) {
      renderer.render(scene, camera);
      return;
    }

    switch (animState) {
      case "in":
        animateToCoords(OUT, 0.05);
        vinylModel.rotation.y += rotationSpeed;
        rotationSpeed = Math.max(rotationSpeed * 0.98, 0.018);
        if (
          vinylModel.position.distanceTo(OUT.position) < 0.01 &&
          Math.abs(vinylModel.rotation.x - OUT.rotation.x) < 0.01
        ) {
          animState = "idle";
          followAmount = 1;
        }
        break;
      case "idle": {
        const targetX = OUT.rotation.x + mouse.y * 0.25;
        const targetZ = OUT.rotation.z + mouse.x * 0.25;
        followAmount = Math.max(0, Math.min(1, followAmount));
        vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.08 * followAmount;
        vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.08 * followAmount;
        break;
      }
      case "out":
        followAmount = Math.max(0, followAmount - 0.07);
        animateToCoords(ENTRY, 0.05);
        {
          const targetX = OUT.rotation.x + mouse.y * 0.25;
          const targetZ = OUT.rotation.z + mouse.x * 0.25;
          vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.08 * followAmount;
          vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.08 * followAmount;
        }
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
    renderer.render(scene, camera);
  }


  window.addEventListener("mousemove", (e) => {
    mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
    controlsNeedsUpdate = true;
  });


  window.addEventListener("resize", () => {
    controlsNeedsUpdate = true;
  });

  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && animState === "idle") {
      animState = "out";
    }
  });

  animate();
}
