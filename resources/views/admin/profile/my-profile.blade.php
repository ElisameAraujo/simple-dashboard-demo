@extends('layouts.admin')
@section('titulo', __('ui.my_profile'))

@section('page-header')
    <div class="page-header">
        <h1>@yield('titulo')</h1>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-regular fa-house"></i>Dashboard
                    </a>
                </li>
                <li>
                    <span>
                        <i class="fa-solid fa-user-gear"></i>{{ __('ui.settings') }}
                    </span>
                </li>
                <li>
                    <span>
                        <i class="fa-solid fa-user"></i>@yield('titulo')
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class="section">
        <div class="section-title">
            <h1>Perfil</h1>
        </div>
        <div class="section-content grid-default">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Imagem de Perfil</div>
                    <div class="action">
                        <div class="avatar-upload">
                            <div class="profile-image">
                                <img src="{{ asset('img/placeholders/default-avatar.jpg') }}" alt="">
                            </div>
                            <div class="image-upload">
                                <button class="btn btn-sm btn-primary w-fit">
                                    {{ __('ui.upload_file') }}
                                </button>
                                <p>{{ __('ui.max_size') }} 3 MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <h1>Informações Pessoais</h1>
            <h4>Gerencie aqui dados básicos da sua conta.</h4>
        </div>
        <div class="section-content">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Nome</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="text" class="input" placeholder="John Doe" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Nome de Usuário</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="text" class="input" placeholder="johnDoe254" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Email</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="email" class="input" placeholder="email@email.com" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option"></div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <button class="btn btn-soft btn-success w-fit">{{ __('ui.save_changes') }}</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <h1>Redes Sociais</h1>
            <h4>Adicione aqui os links para as suas redes sociais.</h4>
        </div>
        <div class="section-content">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Facebook</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="url" class="input" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">X/Twitter</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="url" class="input" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">LinkedIn</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="url" class="input" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option"></div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <button class="btn btn-soft btn-success w-fit">{{ __('ui.save_changes') }}</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
