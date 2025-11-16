@extends('layouts.app')

@section('title', 'Flight Route Map')

@section('content')
<div class="container py-5">

    <h3 class="mb-4 text-center">
        Đường bay: {{ $fromCode }} -> {{ $toCode }}
    </h3>

    <div id="mapWrapper" class="position-relative mx-auto rounded shadow" style="width:900px; max-width:100%;">

        <img id="mapImage"
             src="{{ asset('images/worldMap.png') }}"
             class="d-block w-100 rounded"
             style="display:block; height:auto;">

        <canvas id="mapCanvas"
                class="position-absolute top-0 start-0"
                style="z-index:10; pointer-events:none;"></canvas>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const img = document.getElementById('mapImage');
    const canvas = document.getElementById('mapCanvas');
    const ctx = canvas.getContext('2d');

    // Pixel từ server
    const pix = {
        startX: Number("{{ $startX }}"),
        startY: Number("{{ $startY }}"),
        endX:   Number("{{ $endX }}"),
        endY:   Number("{{ $endY }}")
    };

    function resizeCanvas() {
        const rect = img.getBoundingClientRect();
        canvas.width  = Math.round(rect.width);
        canvas.height = Math.round(rect.height);
        canvas.style.left = img.offsetLeft + 'px';
        canvas.style.top  = img.offsetTop + 'px';
    }

    function drawLine() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Tính tỉ lệ vì ảnh bị resize
        const naturalW = img.naturalWidth;
        const naturalH = img.naturalHeight;

        const scaleX = canvas.width  / naturalW;
        const scaleY = canvas.height / naturalH;

        const p1 = { x: pix.startX * scaleX, y: pix.startY * scaleY };
        const p2 = { x: pix.endX   * scaleX, y: pix.endY   * scaleY };

        // Vẽ đường bay
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#007bff';
        ctx.beginPath();
        ctx.moveTo(p1.x, p1.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.stroke();

        // Vẽ marker đầu – cuối
        ctx.fillStyle = '#fff';
        ctx.strokeStyle = '#007bff';

        ctx.beginPath(); ctx.arc(p1.x, p1.y, 4, 0, Math.PI * 2); ctx.fill(); ctx.stroke();
        ctx.beginPath(); ctx.arc(p2.x, p2.y, 4, 0, Math.PI * 2); ctx.fill(); ctx.stroke();
    }

    function redraw() {
        resizeCanvas();
        drawLine();
    }

    // Vẽ khi ảnh load
    if (img.complete) {
        requestAnimationFrame(redraw);
    } else {
        img.addEventListener('load', () => requestAnimationFrame(redraw));
    }

    // Khi resize window thì vẽ lại
    window.addEventListener('resize', () => requestAnimationFrame(redraw));
});
</script>
@endpush
