import * as THREE from "three";

function loadHtml2Canvas() {
  if (window.html2canvas) return Promise.resolve(window.html2canvas);
  return new Promise((resolve, reject) => {
    const s = document.createElement("script");
    s.src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js";
    s.onload  = () => resolve(window.html2canvas);
    s.onerror = reject;
    document.head.appendChild(s);
  });
}

const vertexShader = /* glsl */`
  attribute vec2  aOffset;
  attribute vec3  aVelocity;
  attribute float aRotSpeed;
  attribute float aDelay;

  uniform float uTime;

  varying vec2  vUv;
  varying float vAlpha;

  void main() {
    float t = max(0.0, uTime - aDelay);

    vec2 local = position.xy - aOffset;
    float angle = aRotSpeed * t;
    float c = cos(angle);
    float s = sin(angle);
    vec2 rotated = vec2(
      c * local.x - s * local.y,
      s * local.x + c * local.y
    );

    vec2 displaced = aOffset + rotated + aVelocity.xy * t;
    displaced.y   -= 7.0 * t * t;

    float z = position.z + aVelocity.z * t;

    vUv   = uv;
    vAlpha = 1.0 - smoothstep(0.7, 1.4, t);

    gl_Position = projectionMatrix * modelViewMatrix * vec4(displaced, z, 1.0);
  }
`;

const fragmentShader = /* glsl */`
  uniform sampler2D uTexture;
  varying vec2  vUv;
  varying float vAlpha;

  void main() {
    vec4 color = texture2D(uTexture, vUv);
    gl_FragColor = vec4(color.rgb, color.a * vAlpha);
  }
`;

function buildShards(W, H, COUNT) {
  const positions = [], uvs = [], aOffset = [],
        aVelocity = [], aRotSpeed = [], aDelay = [],
        indices   = [];
  let vi = 0;

  const COLS = 22, ROWS = 22;
  const tW = W / COLS, tH = H / ROWS;

  for (let row = 0; row < ROWS; row++) {
    for (let col = 0; col < COLS; col++) {
      const x0 = col * tW - W / 2 + (Math.random() - 0.5) * tW * 0.3;
      const x1 = x0 + tW;
      const y0 = H / 2 - row * tH + (Math.random() - 0.5) * tH * 0.3;
      const y1 = y0 - tH;

      const flip = Math.random() > 0.5;

      const tris = flip
        ? [[[x0,y0],[x1,y0],[x1,y1]], [[x0,y0],[x1,y1],[x0,y1]]]
        : [[[x0,y0],[x1,y0],[x0,y1]], [[x1,y0],[x1,y1],[x0,y1]]];

      for (const tri of tris) {
        const cx = (tri[0][0] + tri[1][0] + tri[2][0]) / 3;
        const cy = (tri[0][1] + tri[1][1] + tri[2][1]) / 3;

        const dist = Math.sqrt(cx * cx + cy * cy) || 1;
        const nx = cx / dist, ny = cy / dist;
        const speed = 1.0 + Math.random() * 3.5;
        const vx = nx * speed + (Math.random() - 0.5) * 2.5;
        const vy = ny * speed * 0.4 + Math.random() * 1.5;
        const vz = (Math.random() - 0.5) * 2;
        const rot = (Math.random() - 0.5) * 14;
        const delay = (dist / (Math.max(W, H) * 0.6)) * 0.12 + Math.random() * 0.05;

        for (const [vx2, vy2] of tri) {
          positions.push(vx2, vy2, 0);
          uvs.push(
            (vx2 + W / 2) / W,
            1 - (vy2 + H / 2) / H
          );
          aOffset.push(cx, cy);
          aVelocity.push(vx, vy, vz);
          aRotSpeed.push(rot);
          aDelay.push(delay);
        }
        indices.push(vi, vi + 1, vi + 2);
        vi += 3;
      }
    }
  }

  return { positions, uvs, aOffset, aVelocity, aRotSpeed, aDelay, indices };
}

function doShatter(pageCanvas) {
  const W = window.innerWidth;
  const H = window.innerHeight;

  const renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
  renderer.setSize(W, H);
  renderer.setPixelRatio(Math.min(devicePixelRatio, 1.5));
  Object.assign(renderer.domElement.style, {
    position: "fixed", inset: "0",
    zIndex: "99999", pointerEvents: "none",
  });
  document.body.appendChild(renderer.domElement);

  const camera = new THREE.OrthographicCamera(-W/2, W/2, H/2, -H/2, 0.1, 200);
  camera.position.z = 10;
  const scene = new THREE.Scene();

  const texture = new THREE.CanvasTexture(pageCanvas);

  const data = buildShards(W, H, 300);
  const geo  = new THREE.BufferGeometry();
  geo.setAttribute("position",  new THREE.Float32BufferAttribute(data.positions, 3));
  geo.setAttribute("uv",        new THREE.Float32BufferAttribute(data.uvs, 2));
  geo.setAttribute("aOffset",   new THREE.Float32BufferAttribute(data.aOffset, 2));
  geo.setAttribute("aVelocity", new THREE.Float32BufferAttribute(data.aVelocity, 3));
  geo.setAttribute("aRotSpeed", new THREE.Float32BufferAttribute(data.aRotSpeed, 1));
  geo.setAttribute("aDelay",    new THREE.Float32BufferAttribute(data.aDelay, 1));
  geo.setIndex(data.indices);

  const mat = new THREE.ShaderMaterial({
    uniforms: { uTexture: { value: texture }, uTime: { value: 0 } },
    vertexShader, fragmentShader,
    transparent: true, side: THREE.DoubleSide, depthWrite: false,
  });

  scene.add(new THREE.Mesh(geo, mat));

  let start = null;
  const DURATION = 2.2;

  function loop(ts) {
    if (!start) start = ts;
    const t = (ts - start) / 1000;
    mat.uniforms.uTime.value = t;
    renderer.render(scene, camera);
    if (t < DURATION) {
      requestAnimationFrame(loop);
    } else {
      geo.dispose(); mat.dispose(); texture.dispose();
      renderer.dispose(); renderer.domElement.remove();
    }
  }
  requestAnimationFrame(loop);
}

let triggered = false;

export async function shatter() {
  if (triggered) return;
  triggered = true;

  const html2canvas = await loadHtml2Canvas();

  const canvas = await html2canvas(document.documentElement, {
    useCORS:         true,
    allowTaint:      true,
    backgroundColor: "#0e0d0d",
    scale:           Math.min(devicePixelRatio, 1.5),
    x:               window.scrollX,
    y:               window.scrollY,
    width:           window.innerWidth,
    height:          window.innerHeight,
  });

  doShatter(canvas);
}

window.shatter = shatter;
