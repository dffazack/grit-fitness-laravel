<footer class="bg-primary text-white py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4">
                <h3 class="fw-bold mb-3">GRIT <span class="text-accent">FITNESS</span></h3>
                <p class="text-white-50">
                    Transformasikan tubuh dan pikiranmu bersama GRIT Fitness. Fasilitas modern, 
                    trainer profesional, dan komunitas yang supportif.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-semibold mb-3">Menu</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-accent">
                            Beranda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('classes') }}" class="text-white-50 text-decoration-none hover-accent">
                            Kelas & Trainer
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('membership') }}" class="text-white-50 text-decoration-none hover-accent">
                            Membership
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-lg-3 col-md-4">
                <h5 class="fw-semibold mb-3">Kontak</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt"></i> Plaza Begawan, Tlogomas, Kota Malang, Jawa Timur
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone"></i> +62 881 036 062 600
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope"></i> info@gritfitness.com
                    </li>
                </ul>
            </div>
            
            <!-- Social Media -->
<div class="col-lg-3 col-md-4">
    <h5 class="fw-semibold mb-3">Ikuti Kami</h5>

    <div class="d-flex gap-2 align-items-center">
        <!-- Instagram -->
        <a href="https://www.instagram.com/gritfitness.mlg/" target="_blank" rel="noopener noreferrer"
           class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
           style="width: 40px; height: 40px;" aria-label="Instagram GRIT Fitness">
            <i class="bi bi-instagram" aria-hidden="true"></i>
        </a>

        <!-- TikTok -->
        <a href="https://www.tiktok.com/@gritfitnessmlg" target="_blank" rel="noopener noreferrer"
           class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
           style="width: 40px; height: 40px;" aria-label="TikTok GRIT Fitness">
            <i class="bi bi-tiktok" aria-hidden="true"></i>
        </a>

        <!-- Facebook -->
        <a href="https://www.facebook.com/Gritfitnessmalang/" target="_blank" rel="noopener noreferrer"
           class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
           style="width: 40px; height: 40px;" aria-label="Facebook GRIT Fitness">
            <i class="bi bi-facebook" aria-hidden="true"></i>
        </a>
    </div>
</div>

        
        <hr class="my-4 border-white-50">
        
        <div class="text-center text-white-50">
            <small>&copy; {{ date('Y') }} GRIT Fitness. All rights reserved.</small>
        </div>
    </div>
</footer>
{{-- Modified by: User-Interfaced Team -- }}