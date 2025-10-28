<aside class="bg-primary text-white" style="width: 280px; min-height: 100vh;">
    <div class="p-4">
        <!-- Logo -->
        <div class="mb-4">
            <h4 class="fw-bold mb-0">GRIT <span class="text-accent">Admin</span></h4>
            <small class="text-white-50">Management System</small>
        </div>
        
        <!-- Navigation -->
        <nav class="nav flex-column gap-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Laporan Harian
            </a>
            
            <!-- Members -->
            <a href="{{ route('admin.members.index') }}" 
               class="nav-link text-white {{ request()->routeIs('admin.members.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                <i class="bi bi-people me-2"></i> Kelola Member
            </a>
            
            <!-- Payments -->
            <a href="{{ route('admin.payments.index') }}" 
               class="nav-link text-white {{ request()->routeIs('admin.payments.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                <i class="bi bi-credit-card me-2"></i> Validasi Pembayaran
            </a>
            
            <!-- Schedules -->
            <a href="{{ route('admin.schedules.index') }}" 
               class="nav-link text-white {{ request()->routeIs('admin.schedules.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                <i class="bi bi-calendar3 me-2"></i> Kelola Jadwal
            </a>
            
            <!-- Master Data -->
            <div class="accordion accordion-flush mt-2" id="masterDataAccordion">
                <div class="accordion-item bg-transparent border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-transparent text-white px-0 py-2 shadow-none" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#masterDataCollapse">
                            <i class="bi bi-database me-2"></i> Data Master
                        </button>
                    </h2>
                    <div id="masterDataCollapse" 
                         class="accordion-collapse collapse {{ request()->routeIs('admin.masterdata.*') ? 'show' : '' }}" 
                         data-bs-parent="#masterDataAccordion">
                        <div class="accordion-body px-0 py-2">
                            <a href="{{ route('admin.masterdata.trainers.index') }}" 
                               class="nav-link text-white ps-4 {{ request()->routeIs('admin.masterdata.trainers.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                                <i class="bi bi-award me-2"></i> Trainers
                            </a>
                            <a href="{{ route('admin.masterdata.homepage.index') }}" 
                               class="nav-link text-white ps-4 {{ request()->routeIs('admin.masterdata.homepage.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                                <i class="bi bi-megaphone me-2"></i> Homepage
                            </a>
                            <a href="{{ route('admin.masterdata.notifications.index') }}" 
                               class="nav-link text-white ps-4 {{ request()->routeIs('admin.masterdata.notifications.*') ? 'bg-white bg-opacity-10 rounded' : '' }}">
                                <i class="bi bi-bell me-2"></i> Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Logout -->
        <div class="mt-4 pt-4 border-top border-white border-opacity-25">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>