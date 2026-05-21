@extends('layouts.admin')
@section('titulo', __('ui.security'))

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
                        <i class="fa-solid fa-fingerprint"></i>@yield('titulo')
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')


    <div class="section">
        <div class="section-title">
            <h1>Senha</h1>
            <h4>Atualize aqui a sua senha para entrar na aplicação.</h4>
        </div>
        <div class="section-content">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Senha Atual</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="password" class="input" placeholder="Digite a sua senha atual" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Nova Senha</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="password" class="input" placeholder="Digite a sua nova senha" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Confirme sua Nova Senha</div>
                    <div class="action">
                        <fieldset class="fieldset">
                            <input type="password" class="input" placeholder="Confirme a sua nova senha" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option"></div>
                    <div class="action flex-row flex gap-2 justify-start">
                        <fieldset class="fieldset">
                            <button class="btn btn-soft btn-success w-fit">{{ __('ui.update') }}</button>
                        </fieldset>

                        <fieldset class="fieldset">
                            <button class="btn btn-soft btn-warning w-fit">{{ __('ui.forgot_my_password') }}</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="section">
        <div class="section-title">
            <h1>Remover Conta</h1>
            <h4>Se você não precisa mais da sua conta, pode removê-la aqui.</h4>
        </div>
        <div class="section-content">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Remoção de Conta</div>
                    <div class="action flex-col flex gap-2 justify-start">
                        <button class="btn btn-soft btn-error w-fit">{{ __('ui.delete_my_account') }}</button>
                        <p>Essa ação apagará todos os seus dados. Esta ação é irreversível, então, prossiga
                            com cuidado.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
