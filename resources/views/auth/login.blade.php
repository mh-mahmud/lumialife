<!DOCTYPE html>
<html lang="en" style="height: 100%;">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Admin & Agent Login</title>
        <link type="image/x-icon" rel="shortcut icon" href="{{url('/')}}/assets/media/logos/fox-technologies.jpg"/>
        <!-- <link rel="preload" href="/web/static/src/libs/fontawesome/fonts/fontawesome-webfont.woff2?v=4.7.0" as="font" crossorigin=""/> -->
        <!-- <link type="text/css" rel="stylesheet" href="web.assets_frontend.min.css"/> -->
        <link type="text/css" rel="stylesheet" href="{{url('/')}}/assets/login/web.assets_frontend.min.css"/>
        <link href="{{url('/')}}/assets/css/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
        <script src="{{url('/')}}/assets/js/sweetalert2.min.js"></script>
        <!-- <script type="text/javascript">
            odoo.__session_info__ = {"is_admin": false, "is_system": false, "is_public": true, "is_website_user": false, "user_id": false, "uid": false, "is_frontend": true, "profile_session": null, "profile_collectors": null, "profile_params": null, "show_effect": true, "currencies": {"55": {"symbol": "\u09f3", "position": "after", "digits": [69, 2]}}, "bundle_params": {"lang": "en_US"}, "test_mode": false, "websocket_worker_version": "17.4-2", "translationURL": "/website/translations", "cache_hashes": {"translations": "5546cfff153b9e36c0aa4bdbcfc09f75c561342a"}};
            if (!/(^|;\s)tz=/.test(document.cookie)) {
                const userTZ = Intl.DateTimeFormat().resolvedOptions().timeZone;
                document.cookie = `tz=${userTZ}; path=/`;
            }
        </script> -->
        <!-- <script type="text/javascript" defer="defer" src="/web/assets/30d2e7a/web.assets_frontend_minimal.min.js" onerror="__odooAssetError=1"></script>
        <script type="text/javascript" defer="defer" data-src="/web/assets/c127cd3/web.assets_frontend_lazy.min.js" onerror="__odooAssetError=1"></script> -->
        
    </head>
    <body class="o_home_menu_background">
        <div id="wrapwrap" class="  ">

                <!-- <a class="o_skip_to_content btn btn-primary rounded-0 visually-hidden-focusable position-absolute start-0" href="#wrap" tabindex="2">Skip to Content</a> -->
                <main>



                    <div class="container py-5">
                        <div style="max-width: 300px;" class="card border-0 mx-auto bg-100 rounded-0 shadow-sm bg-white o_database_list">

                            @if (session('success'))
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: '{{ session('success')}}',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                </script>
                            @endif

                            @if (session('error'))
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: '{{ session('error')}}',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                </script>
                            @endif

                            <div class="card-body">
                                <div class="text-center pb-3 border-bottom mb-4">
                                    <img alt="Logo" style="max-height:120px; max-width: 40%; width:auto" src="{{url('/')}}/assets/media/logos/fox-technologies.jpg"/>
                                    <h5 class="mt-3 mb-0">Admin & Agent Login</h5>
                                    <small class="text-muted">Back-office access only</small>
                                </div>
                                
                                <form class="oe_login_form" role="form" method="post" action="{{ route('login.post') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                                    <div class="mb-3 field-login">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" placeholder="" name="email" autofocus="autofocus" autocomplete="off" class="form-control "/>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" placeholder="" name="password" autocomplete="off" maxlength="4096" class="form-control "/>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>


                                    <div class="clearfix oe_login_buttons text-center gap-1 d-grid mb-1 pt-3">
                                        <button type="submit" class="btn btn-primary">Log in</button>
                                    <div class="justify-content-between mt-2 d-flex small">
                                        <a href="#">Reset Password</a>
                                    </div>
                                        <div class="o_login_auth"></div>
                                    </div>

                                    <!-- <input type="hidden" name="type" value="password"/>
                                    <input type="hidden" name="redirect" value="/odoo/crm/2?"/> -->
                                </form>
                
                                <div class="text-center small mt-4 pt-3 border-top">
                                    <a href="https://foxtechnologies.net/" target="_blank">Powered by <span>Fox Technologies</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </main>
                
            </div>
        </body>
</html>
