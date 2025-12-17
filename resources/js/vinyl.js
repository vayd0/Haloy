import * as THREE from "three";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";

const scene = new THREE.Scene();

const camera = new THREE.PerspectiveCamera(
  45,
  window.innerWidth / window.innerHeight,
  0.1,
  100
);
camera.position.set(0, 3, 8);

const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(window.devicePixelRatio);
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.outputColorSpace = THREE.SRGBColorSpace;
document.getElementById("scene-container").appendChild(renderer.domElement);

const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

const dirLight = new THREE.DirectionalLight(0xffffff, 12);
dirLight.position.set(5, 10, 7);
scene.add(dirLight);

const ambientLight = new THREE.AmbientLight(0xffffff, 3);
scene.add(ambientLight);

const loader = new GLTFLoader();
loader.load(
  "/3D/vinyl.glb",
  function (gltf) {
    const vinylModel = gltf.scene;
    const glassMaterial = new THREE.MeshPhysicalMaterial({
      color: 0xffffff,
      metalness: 0,
      roughness: 0,
      transmission: 1,
      thickness: 2.5, // plus d'épaisseur pour la réfraction
      ior: 1.7, // indice de réfraction plus marqué
      opacity: 1,
      transparent: true,
      reflectivity: 0.5,
      clearcoat: 1,
      clearcoatRoughness: 0,
      envMapIntensity: 2,
      specularIntensity: 1,
      specularColor: 0xffffff,
      attenuationColor: 0xffffff,
      attenuationDistance: 0.5,
    });
    vinylModel.traverse((child) => {
      if (child.isMesh) {
        child.material = glassMaterial;
        child.castShadow = true;
        child.receiveShadow = true;
      }
    });

    // Applique la rotation Y avant le centrage
    vinylModel.rotation.y = Math.PI / 2;

    // Centrage automatique du modèle (après la rotation)
    const box = new THREE.Box3().setFromObject(vinylModel);
    const center = box.getCenter(new THREE.Vector3());
    vinylModel.position.sub(center);

    scene.add(vinylModel);
  },
  undefined,
  function (error) {
    console.error("Erreur lors du chargement du GLB:", error);
  }
);

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
}
animate();

window.addEventListener("resize", () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});
