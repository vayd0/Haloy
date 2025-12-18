import * as THREE from "three";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { RGBELoader } from "three/examples/jsm/loaders/RGBELoader.js";

const dev = false;

if (!dev) {
  // --- CONFIGURATION SCÈNE ---
  const scene = new THREE.Scene();
  const mouse = new THREE.Vector2();
  const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
  camera.position.set(0, 3, 8);

  const renderer = new THREE.WebGLRenderer({ 
    antialias: true, 
    alpha: true,
    powerPreference: "high-performance" 
  });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  
  const container = document.getElementById("scene-container");
  if (container) container.appendChild(renderer.domElement);

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.enablePan = false;

  scene.add(new THREE.AmbientLight(0xffffff, 1.5));
  const dirLight = new THREE.DirectionalLight(0xffffff, 2);
  dirLight.position.set(5, 10, 7);
  scene.add(dirLight);

  // --- COORDONNÉES ANIMATION (modifiables facilement) ---
  // Modifie ces deux variables pour changer l'entrée et la sortie
  const ENTRY = { position: new THREE.Vector3(0, 0.5, 8), rotation: new THREE.Euler(0.2, 0, 0) };
  const OUT   = { position: new THREE.Vector3(0.2, 1, 0.5), rotation: new THREE.Euler(1.2, 0.5, 1) };

  // --- CHARGEMENT DES ASSETS ---
  let vinylModel;
  let animState = "in"; // "in", "idle", "out"
  let rotationSpeed = 0.14;
  let time = 0;
  let followAmount = 1; // 1 = suit le curseur, 0 = plus du tout

  function setVinylCoords(coords) {
    vinylModel.position.copy(coords.position);
    vinylModel.rotation.copy(coords.rotation);
  }

  function animateToCoords(target, speed = 0.05) {
    vinylModel.position.lerp(target.position, speed);
    vinylModel.rotation.x = THREE.MathUtils.lerp(vinylModel.rotation.x, target.rotation.x, speed);
    vinylModel.rotation.y = THREE.MathUtils.lerp(vinylModel.rotation.y, target.rotation.y, speed);
    vinylModel.rotation.z = THREE.MathUtils.lerp(vinylModel.rotation.z, target.rotation.z, speed);
  }

  new RGBELoader().load("https://dl.polyhaven.org/file/ph-assets/HDRIs/hdr/1k/studio_small_08_1k.hdr", (hdrMap) => {
    hdrMap.mapping = THREE.EquirectangularReflectionMapping;
    scene.environment = hdrMap;

    const loader = new GLTFLoader();
    loader.load("/3D/vinyl.glb", (gltf) => {
      vinylModel = gltf.scene;
      vinylModel.scale.setScalar(0.6);

      // Centrage
      const box = new THREE.Box3().setFromObject(vinylModel);
      const center = box.getCenter(new THREE.Vector3());
      vinylModel.position.sub(center);

      // Position et rotation de départ (entrée)
      setVinylCoords(ENTRY);

      // Matériau chrome pur
      const chromeMat = new THREE.MeshPhysicalMaterial({
        color: 0xcccccc,
        metalness: 1,
        roughness: 0.04,
        envMap: scene.environment,
        envMapIntensity: 3.5,
        clearcoat: 1,
        clearcoatRoughness: 0.01,
        ior: 2.5,
      });

      vinylModel.traverse((child) => {
        if (child.isMesh) child.material = chromeMat;
      });
      scene.add(vinylModel);
    });
  });

  // --- ANIMATION ---
  function animate() {
    requestAnimationFrame(animate);
    controls.update();

    if (vinylModel) {
      if (animState === "in") {
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
      } else if (animState === "idle") {
        // Suivi du curseur (toujours fluide)
        const targetX = OUT.rotation.x + mouse.y * 0.25;
        const targetZ = OUT.rotation.z + mouse.x * 0.25;
        vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.08 * followAmount;
        vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.08 * followAmount;
      } else if (animState === "out") {
        // On diminue progressivement le followAmount pour estomper le suivi du curseur
        followAmount = Math.max(0, followAmount - 0.07);
        // On interpole la rotation et la position vers ENTRY
        animateToCoords(ENTRY, 0.05);
        // On continue d'estomper le suivi du curseur pendant la sortie
        const targetX = OUT.rotation.x + mouse.y * 0.25;
        const targetZ = OUT.rotation.z + mouse.x * 0.25;
        vinylModel.rotation.x += (targetX - vinylModel.rotation.x) * 0.08 * followAmount;
        vinylModel.rotation.z += (targetZ - vinylModel.rotation.z) * 0.08 * followAmount;

        if (
          vinylModel.position.distanceTo(ENTRY.position) < 0.01 &&
          Math.abs(vinylModel.rotation.x - ENTRY.rotation.x) < 0.01 &&
          Math.abs(vinylModel.rotation.y - ENTRY.rotation.y) < 0.01 &&
          Math.abs(vinylModel.rotation.z - ENTRY.rotation.z) < 0.01
        ) {
          setVinylCoords(ENTRY);
          followAmount = 0;
        }
      }
    }

    renderer.render(scene, camera);
  }

  // --- ÉCOUTEURS D'ÉVÉNEMENTS ---
  window.addEventListener("mousemove", (e) => {
    mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
  });

  window.addEventListener("resize", () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && animState === "idle") {
      animState = "out";
    }
  });

  animate();
}