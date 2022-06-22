@if ($poste > -2)

    <body class="g-sidenav-show  bg-gray-100">
        @if (Request::is('dashboard'))
            <div id="loader-wrapper">
                <div class="loader">
                    <img src="img/loader.gif">
                </div>
            </div>
        @endif

        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-left ms-3"
            id="sidenav-main" data-color="dark">
            <div class="sidenav-header">
                <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute right-0 top-0 d-none d-xl-none"
                    aria-hidden="true" id="iconSidenav"></i>
                <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
                    <img src="{{ request()->is('dashboard') ? 'img/logo-e-cours.png' : '../img/logo-e-cours.png' }}"
                        class="navbar-brand-img h-100 w-100" alt="logo">
                </a>
            </div>
            <hr class="horizontal dark mt-0">
            <div class="ha-w" id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : 'ha-nav' }}"
                            href="{{ route('dashboard') }}">
                            <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                <i class="fas fa-home-alt mt-0 {{ request()->is('dashboard') ? ' text-white' : ' text-dark ' }}" ></i>
                            </div>
                            <span class="nav-link-text ms-1" >Accueil</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->is('dashboard/generePV') ? 'active' : 'ha-nav' }}"
                            href="{{ route('generePV') }}">
                            <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                <i class="far fa-poll-h mt-0 {{ request()->is('dashboard/generePV') ? ' text-white' : ' text-dark ' }}" ></i>
                            </div>
                            <span class="nav-link-text ms-1">Générer PV</span>
                        </a>
                    </li>
                    @if ($poste == -1)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/gestionNote') ? 'active' : 'ha-nav' }} mb-0"
                                href="{{ route('gestionNote') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fad fa-book-reader mt-0 {{ request()->is('dashboard/gestionNote') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Gestion des <br> Notes</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/gestionEtudiant') ? 'active' : 'ha-nav' }} mb-0"
                                href="{{ route('gestionEtudiant') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fad fa-user-graduate mt-0 {{ request()->is('dashboard/gestionEtudiant') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Gestion des <br> Etudiants</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/gestionEnseignant') ? 'active' : 'ha-nav' }} mb-0 "
                                href="{{ route('gestionEnseignant') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fad fa-user-tie mt-0 {{ request()->is('dashboard/gestionEnseignant') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1 text-center">Gestion des <br> Enseignants</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/gestionModule') ? 'active' : 'ha-nav' }} mb-0"
                                href="{{ route('gestionModule') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fad fa-books mt-0 {{ request()->is('dashboard/gestionModule') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Gestion des <br> Modules</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/gestionFiliere') ? 'active' : 'ha-nav' }} mb-0"
                                href="{{ route('gestionFiliere') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fad fa-graduation-cap mt-0 {{ request()->is('dashboard/gestionFiliere') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Gestion des <br> Filières</span>
                            </a>
                        </li>
                    @endif
                    @if ($poste > -1)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/remplirNotes') ? 'active' : 'ha-nav' }}"
                                href="{{ route('remplirNotes') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fal fa-table mt-0 {{ request()->is('dashboard/remplirNotes') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Remplissage des <br>Notes</span>
                            </a>
                        </li>
                    @endif
                    @if ($poste > 0)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard/affectation') ? 'active' : 'ha-nav' }}"
                                href="{{ route('affectation') }}">
                                <div class="icon icon-shape icon-md shadow border-radius-md bg-white text-center align-items-center justify-content-center">
                                    <i class="fas fa-users-class mt-0 {{ request()->is('dashboard/affectation') ? ' text-white' : ' text-dark ' }}" ></i>
                                </div>
                                <span class="nav-link-text ms-1">Affectation des <br>Modules</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </aside>
        <main class="main-content mt-1 border-radius-lg">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
                navbar-scroll="false">
                <div class="container-fluid py-1 px-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                                    href="{{ route('dashboard') }}">Tableau de Bord</a>
                            </li>
                            <li class="breadcrumb-item text-dark active ha-poste font-weight-bolder"
                                id="{{ $poste }}" aria-current="page">{{ $name }}</li>
                        </ol>
                    </nav>
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

                        <ul class="navbar-nav ms-md-auto justify-content-end">
                            <li class="nav-item d-flex align-items-center ">
                                <a href="{{ route('profile') }}" class="nav-link text-body font-weight-bold px-0">
                                    <i class="fal fa-user ha-acc"></i><span
                                            class="d-sm-inline text-uppercase text-secondary ha-acc">&nbsp;&nbsp;{{ $nom }}&nbsp;&nbsp;{{ $prenom }}</span>
                                </a>

                            </li>
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item d-flex align-items-center">
                                <a href="{{ route('logout') }}" type="submit"
                                    class="nav-link text-body font-weight-bold px-3">
                                    <i class="ni ni-button-power ha-dec " data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Déconnexion" aria-hidden="true"></i>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

@endif
