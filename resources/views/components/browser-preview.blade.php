@props(['compact' => false])

<div {{ $attributes->class(['browser-card', 'browser-card-compact' => $compact]) }}>
    <div class="browser-top">
        <span></span>
        <span></span>
        <span></span>
        <nav>Produk&nbsp;&nbsp;&nbsp;Jurnal&nbsp;&nbsp;&nbsp;Tentang&nbsp;&nbsp;&nbsp;Profil</nav>
    </div>
    <div class="browser-content">
        <div class="browser-logo">
            <strong>SILERA</strong>
            <small>Sistem Informasi Literasi Riau</small>
        </div>
        <div class="browser-text">
            <h3>Tentang Laman</h3>
            <p>Laman Komunitas Literasi Provinsi Riau menjadi pusat informasi, pendataan, dan jejaring bagi taman bacaan, forum, serta pegiat literasi.</p>
            <p>Data yang terkelola membantu kolaborasi program literasi di berbagai kabupaten dan kota.</p>
        </div>
    </div>
</div>
