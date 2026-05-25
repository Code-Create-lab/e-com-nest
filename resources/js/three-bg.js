import * as THREE from 'three';

/**
 * Light-theme 3D ambient background.
 * Renders soft, floating low-poly shapes into a target canvas.
 * Honors prefers-reduced-motion.
 */
export function mountThreeBackground(canvas, options = {}) {
    if (!canvas) return null;

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return null;

    const palette = options.palette ?? [0xbfdbfe, 0xfde68a, 0xa7f3d0, 0xfecdd3, 0xc7d2fe];
    const intensity = options.intensity ?? 1;
    const shapeCount = options.shapeCount ?? 9;

    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(canvas.clientWidth, canvas.clientHeight, false);

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.z = 10;

    scene.add(new THREE.AmbientLight(0xffffff, 0.95));
    const key = new THREE.DirectionalLight(0xffffff, 0.7);
    key.position.set(5, 6, 8);
    scene.add(key);
    const rim = new THREE.DirectionalLight(0xc7d2fe, 0.35);
    rim.position.set(-6, -4, 4);
    scene.add(rim);

    const geometries = [
        new THREE.IcosahedronGeometry(0.9, 0),
        new THREE.TorusGeometry(0.7, 0.25, 16, 64),
        new THREE.OctahedronGeometry(0.85, 0),
        new THREE.DodecahedronGeometry(0.78, 0),
        new THREE.TorusKnotGeometry(0.55, 0.18, 64, 12),
    ];

    const shapes = [];
    for (let i = 0; i < shapeCount; i++) {
        const geo = geometries[i % geometries.length];
        const color = palette[i % palette.length];
        const mat = new THREE.MeshPhysicalMaterial({
            color,
            roughness: 0.45,
            metalness: 0.05,
            transmission: 0.25,
            thickness: 0.6,
            transparent: true,
            opacity: 0.85,
            clearcoat: 0.4,
            clearcoatRoughness: 0.6,
        });
        const mesh = new THREE.Mesh(geo, mat);
        mesh.position.set(
            (Math.random() - 0.5) * 11,
            (Math.random() - 0.5) * 7,
            (Math.random() - 0.5) * 6 - 1,
        );
        const scale = 0.55 + Math.random() * 0.8;
        mesh.scale.setScalar(scale * intensity);
        mesh.userData.spin = {
            x: (Math.random() - 0.5) * 0.003,
            y: (Math.random() - 0.5) * 0.003,
            z: (Math.random() - 0.5) * 0.002,
        };
        mesh.userData.float = {
            phase: Math.random() * Math.PI * 2,
            amp: 0.2 + Math.random() * 0.35,
            speed: 0.3 + Math.random() * 0.4,
            base: mesh.position.y,
        };
        scene.add(mesh);
        shapes.push(mesh);
    }

    const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
    const onPointerMove = (e) => {
        const r = canvas.getBoundingClientRect();
        pointer.tx = ((e.clientX - r.left) / r.width - 0.5) * 0.6;
        pointer.ty = ((e.clientY - r.top) / r.height - 0.5) * 0.6;
    };
    window.addEventListener('pointermove', onPointerMove, { passive: true });

    const resize = () => {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    };
    const ro = new ResizeObserver(resize);
    ro.observe(canvas);

    let raf = 0;
    let last = performance.now();
    const tick = (now) => {
        const dt = Math.min(0.05, (now - last) / 1000);
        last = now;

        pointer.x += (pointer.tx - pointer.x) * 0.05;
        pointer.y += (pointer.ty - pointer.y) * 0.05;
        camera.position.x = pointer.x * 1.5;
        camera.position.y = -pointer.y * 1.2;
        camera.lookAt(0, 0, 0);

        for (const m of shapes) {
            m.rotation.x += m.userData.spin.x;
            m.rotation.y += m.userData.spin.y;
            m.rotation.z += m.userData.spin.z;
            m.userData.float.phase += dt * m.userData.float.speed;
            m.position.y = m.userData.float.base + Math.sin(m.userData.float.phase) * m.userData.float.amp;
        }

        renderer.render(scene, camera);
        raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    return () => {
        cancelAnimationFrame(raf);
        ro.disconnect();
        window.removeEventListener('pointermove', onPointerMove);
        shapes.forEach((m) => {
            m.geometry.dispose();
            m.material.dispose();
        });
        renderer.dispose();
    };
}

export function autoMountBackgrounds(root = document) {
    root.querySelectorAll('canvas[data-three-bg]').forEach((canvas) => {
        if (canvas.dataset.threeMounted) return;
        canvas.dataset.threeMounted = '1';

        const palettes = {
            light: [0xbfdbfe, 0xfde68a, 0xa7f3d0, 0xfecdd3, 0xc7d2fe],
            sky: [0xbae6fd, 0xc7d2fe, 0xddd6fe, 0xfde68a],
            mono: [0xe4e4e7, 0xfafafa, 0xd4d4d8, 0xf4f4f5],
            warm: [0xfde68a, 0xfecdd3, 0xfed7aa, 0xfef3c7],
        };
        const variant = canvas.getAttribute('data-three-bg') || 'light';
        const intensity = parseFloat(canvas.getAttribute('data-intensity') || '1');
        const count = parseInt(canvas.getAttribute('data-count') || '9', 10);

        mountThreeBackground(canvas, {
            palette: palettes[variant] ?? palettes.light,
            intensity,
            shapeCount: count,
        });
    });
}
