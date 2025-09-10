<!-- App Bottom Menu -->
<style>
    .appBottomMenu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background: #fff;
        border-top: 1px solid #ddd;
        padding: 8px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .appBottomMenu .item {
        flex: 1;
        text-align: center;
        font-size: 12px;
        color: #000;
        text-decoration: none;
    }

    .appBottomMenu .item.active strong {
        color: #F7A129;
    }

    .appBottomMenu .item img {
        width: 26px;
        height: 26px;
        display: block;
        margin: 0 auto 3px;
    }

    /* Kamera khusus */
    .camera-button {
        background: #F7A129;
        padding: 16px;
        border-radius: 20%;
        transform: rotate(45deg);
        display: inline-block;
        margin-top: -35px;
    }

    .camera-button img {
        transform: rotate(-45deg);
        width: 28px;
        height: 28px;
    }
</style>

<div class="appBottomMenu">
    <!-- Home -->
    <a href="#" class="item active">
        <div class="col">
            <img src="https://s3-alpha-sig.figma.com/img/12a8/0cc4/88f877f...">
            <strong>Beranda</strong>
        </div>
    </a>

    <!-- Rekap -->
    <a href="#" class="item">
        <div class="col">
            <img src="https://s3-alpha-sig.figma.com/img/6d36/fc3a/f9f9f...">
            <strong>Rekap</strong>
        </div>
    </a>

    <!-- Kamera -->
    <a href="#" class="item">
        <div class="col">
            <div class="camera-button">
                <img src="https://s3-alpha-sig.figma.com/img/fda2/7be2/93f...">
            </div>
        </div>
    </a>

    <!-- Cuti -->
    <a href="#" class="item">
        <div class="col">
            <img src="https://s3-alpha-sig.figma.com/img/d9bc/aa65/92e...">
            <strong>Cuti</strong>
        </div>
    </a>

    <!-- Akun -->
    <a href="#" class="item">
        <div class="col">
            <img src="https://s3-alpha-sig.figma.com/img/81a0/c748/a42...">
            <strong>Akun</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->
