@extends('layouts.admin')
@section('titulo', __('ui.notifications'))

@section('page-header')
    <div class="page-header">
        <h1>@yield('titulo')</h1>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}">
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
                        <i class="fa-regular fa-bell"></i>@yield('titulo')
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class="section">
        <div class="section-title">
            <h1>Newsletter</h1>
        </div>
        <div class="section-content grid-default">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sobre notícias mais vistas da semana
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar quando houver uma nova análise de faixa/álbum do meu artista
                        favorito
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <h1>Comentários</h1>
        </div>
        <div class="section-content grid-default">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sempre que alguém citar meu comentário
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sempre que alguém curtir meu comentário
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <h1>Análises</h1>
        </div>
        <div class="section-content grid-default">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sempre que houver uma nova análise de álbum
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sempre que houver uma nova análise de faixa
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="profile-option">
                    <div class="option-notification">
                        Notificar sempre que alguém curtir minha análise
                    </div>
                    <div class="action-notification">
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" checked="checked"
                                    class="checkbox checkbox-sm rounded checkbox-primary" />
                            </label>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">
            <h1>Silenciar Notificações</h1>
            <h4>Desative suas notificações por um período determinado por você.</h4>
        </div>
        <div class="section-content">
            <div class="profile-options">
                <div class="profile-option">
                    <div class="option">Não pertubar</div>
                    <div class="action">
                        <input class="btn" type="checkbox" name="notification-day"
                            aria-label="{{ __('ui.pause_all_notifications') }}" />
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Definir Período</div>
                    <div class="action">
                        <p>Não me notificar entre:</p>
                        <fieldset class="fieldset">
                            <input type="time" class="input" />
                        </fieldset>

                        <fieldset class="fieldset">
                            <input type="time" class="input" />
                        </fieldset>
                    </div>
                </div>

                <div class="profile-option">
                    <div class="option">Não me notificar nos seguintes dias:</div>
                    <div class="action">
                        <div class="join">
                            @foreach (__('dates.weekdays') as $weekday)
                                <input class="join-item btn" type="checkbox" name="notification-day"
                                    aria-label="{{ $weekday }}" />
                            @endforeach
                        </div>
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
