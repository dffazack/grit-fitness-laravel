<footer class="bg-primary text-white py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4">
                <h3 class="fw-bold mb-3">GRIT <span class="text-accent">Fitness</span></h3>
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
                        <i class="bi bi-geo-alt"></i> Jl. Fitness No. 123, Jakarta
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone"></i> +62 812 3456 7890
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope"></i> info@gritfitness.com
                    </li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div class="col-lg-3 col-md-4">
                <h5 class="fw-semibold mb-3">Ikuti Kami</h5>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bi bi-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="my-4 border-white-50">
        
        <div class="text-center text-white-50">
            <small>&copy; {{ date('Y') }} GRIT Fitness. All rights reserved.</small>
        </div>
    </div>
</footer>