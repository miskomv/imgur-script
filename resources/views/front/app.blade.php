<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <title>{{ $page_title }}</title>
    <header>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />

        <meta name="description" content="{{ $page_description }}" />
        <meta name="keywords" content="{{ $page_keywords }}" />

        <!--Responsive-->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="viewport" id="viewport" content="initial-scale=1,minimum-scale=1,width=device-width,height=device-height,target-densitydpi=device-dpi,user-scalable=no" />

        <!--Facebook-->
        <meta property="og:url" content="http://{{ $page_domain }}{{ $page_url }}" />
        <meta property="og:type" content="article" />
        <meta property="og:image" content="http://{{ $page_domain }}{{ $page_image }}" />
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="315" />
        <meta property="og:description" content="{{ $page_description }}" />

        <!--Twitter-->
        <meta name="twitter:site" content="@imgur" />
        <meta name="twitter:domain" content="{{ $page_domain }}" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" content="http://{{ $page_domain }}{{ $page_image }}" />
        <meta name="twitter:description" content="{{ $page_description }}" />

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,700,700i" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('front/assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('front/assets/css/style.css') }}">

        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('front/assets/js/libs/es6-promise.auto.js') }}"></script>
        <script src="{{ URL::asset('front/assets/js/libs/axios.js') }}"></script>
        <script src="{{ URL::asset('front/assets/js/libs/vue.js') }}"></script>
        <script src="{{ URL::asset('front/assets/js/libs/vue-router.js') }}"></script>
        <script src="{{ URL::asset('front/assets/js/app.js') }}"></script>

    </header>
    <body>
        <div id="app" :class="{visible:app.loaded}">

            <!--Menu-->
            <div class="menu">

                <a href="/">
                    <img src="{{ URL::asset('front/assets/images/logo-web.png') }}">
                </a>

                <ul>
                    <li>
                        <a href="#" @click.prevent="uploadFileShow">
                            <i class="fas fa-upload"></i> Upload
                        </a>
                    </li>
                </ul>

            </div>

            <!--Home-->
            <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                <div class="list" v-if="isView('home')">
                    <h1>Home</h1>
                    <div class="image" v-for="image in image_list" :style="{backgroundImage: 'url(' + image.thumbnail + ')'}">
                        <div class="capa_hover" @click.prevent="detailsShow(image)"><i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    <div class="image hidden" v-for="i in 8"></div>
                </div>
            </transition>

            <!--Uploaded images-->
            <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                <div class="list" v-if="isView('uploaded-files')">
                    <h1>
                        <a href="#" @click.prevent="loadHome"><i class="fas fa-home"></i></a>
                        » Uploaded files
                    </h1>
                    <div class="image" v-for="image in image_list" :style="{backgroundImage: 'url(' + image.thumbnail + ')'}">
                        <div class="capa_hover" @click.prevent="detailsShow(image)"><i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    <div class="image hidden" v-for="i in 8"></div>
                </div>
            </transition>

            <!--Image Details-->
            <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                <div class="details" v-if="details.visible">
                    <a href="#" @click.prevent="detailsHide" class="btn_cerrar">
                        <i class="fas fa-window-close"></i>
                    </a>
                    <div class="share">

                        <a href="#" @click.prevent="shareOnFacebook">
                            <i class="fab fa-facebook-square"></i>
                        </a>

                        <a href="#" @click.prevent="shareOnTwitter">
                            <i class="fab fa-twitter-square"></i>
                        </a>

                    </div>
                    <img src="" :src="details.data.path">
                </div>
            </transition>

            <!--Popup New File-->
            <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                <div class="new_file" v-if="new_file.visible">

                    <div class="popup">
                        <a href="#" class="close" @click.prevent="uploadFileHide"><i class="fas fa-window-close"></i>
                        </a>
                        <h1>Upload image</h1>
                        <p id="dropZone" @click.prevent="uploadFileSelectItems">
                            Drag the image here to upload it to the server or click to select it from your computer.
                        </p>
                        <input type="file" ref="image_file" multiple accept="image/*" @change="uploadFileItemsSelected">
                    </div>

                </div>
            </transition>

            <!--Popup Loader-->
            <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                <div class="loader" v-if="loader.visible">
                    <img src="{{ URL::asset('front/assets/images/loading.svg') }}">
                    <div>
                        <p v-for="upload,index in new_file.uploading" v-if="upload.porcentaje < 100" class="upload">
                            <span v-html="upload.uploaded"></span> / <span v-html="upload.total"></span>
                            <progress :value="upload.porcentaje" max="100"></progress>
                        </p>
                    </div>
                </div>
            </transition>

        </div>
    </body>
</html>