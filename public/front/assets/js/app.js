window.onload = function () {

	var router = new VueRouter( { mode: 'history', routes: [] } );

	// window.onpopstate = window.mainHandler.onPopState;

	new Vue( {
		el     : '#app',
		router : router,
		data   : {
			app   : {
				loaded: false,
				path  : ''
			},
			loader: {
				visible: false
			},
			view  : {
				selected: 'list'
			},

			image_list: [],

			new_file: {
				visible        : false,
				uploading_items: 0,
				errors         : false
			},
			details : {
				visible: false,
				data   : ''
			}

		},
		mounted: function () {
			this.checkRoute();
		},
		methods: {

			checkRoute          : function () {

				this.app.path = this.$route.path;

				this.loadHome();

				if ( this.app.path !== '/' )
					setTimeout( this.loadImageURL, 350 );

			},
			loadImageURL        : function () {

				var endpoint = "/images/info" + this.app.path;
				axios.get( endpoint ).then( this.loadImageURLCallback );

			},
			loadImageURLCallback: function ( response ) {
				this.detailsShow( response.data );
			},

			isView   : function ( view ) {
				return (this.view.selected === view);
			},
			setView  : function ( new_view ) {
				this.view.selected = new_view;
			},
			appLoaded: function () {
				this.app.loaded = true;
			},

			loadHome        : function () {
				this.setView( 'home' );
				this.image_list = [];
				axios.get( "/images/list" ).then( this.loadHomeCallback );

			},
			loadHomeCallback: function ( response ) {
				this.image_list = response.data;
				this.appLoaded();
			},

			uploadFileShow: function () {

				this.new_file.visible = true;

				this.$nextTick( function () {
					document.getElementById( 'dropZone' ).addEventListener( 'drop', this.uploadFileDropItems, true );
					document.getElementById( 'dropZone' ).addEventListener( 'dragover', this.uploadFilePrevent, true );
				} )
			},
			uploadFileHide: function () {
				document.getElementById( 'dropZone' ).removeEventListener( 'drop', this.uploadFileDropItems, true );
				document.getElementById( 'dropZone' ).removeEventListener( 'dragover', this.uploadFilePrevent, true );
				this.new_file.visible = false;
			},

			uploadFileSelectItems  : function () {
				this.$refs[ 'image_file' ].click();
			},
			uploadFileItemsSelected: function () {

				this.loader.visible = true;
				this.uploadFiles( this.$refs[ 'image_file' ].files );
			},

			uploadFileDropItems: function ( event ) {

				this.loader.visible = true;

				this.uploadFilePrevent( event );

				var dt    = event.dataTransfer;
				var files = dt.files;
				this.uploadFiles( files );
			},
			uploadFilePrevent  : function ( event ) {

				event.preventDefault();
				event.stopPropagation();

			},

			uploadFiles         : function ( files ) {

				this.image_list      = [];
				this.new_file.errors = false;
				this.setView( 'uploaded-files' );

				for ( var i = 0; i < files.length; i++ )
					this.uploadFile( files[ i ] );

			},
			uploadFile          : function ( file ) {

				this.new_file.uploading_items++;

				var formData = new FormData();
				formData.append( 'imagen', file );

				axios.post( "/images/upload", formData ).then( this.uploadFileCallbackOK ).catch( this.uploadFileCallbackKO );

			},
			uploadFileCallbackOK: function ( response ) {

				this.new_file.uploading_items--;
				this.image_list.push( response.data );

				if ( this.new_file.uploading_items === 0 )
					this.uploadFileDone();

			},
			uploadFileCallbackKO: function () {
				this.new_file.uploading_items--;
				this.new_file.errors = true;

				if ( this.new_file.uploading_items === 0 )
					this.uploadFileDone();
			},
			uploadFileDone      : function () {
				this.new_file.visible = false;
				this.loader.visible   = false;

				if ( this.new_file.errors )
					this.uploadFileShowErrors();

			},
			uploadFileShowErrors: function () {
				alert( "Some files couldn't be uploaded" );

				if ( this.image_list.length === 0 )
					this.setView( 'home' );
			},

			detailsShow: function ( image ) {
				this.details.data    = image;
				this.details.visible = true;
				window.history.pushState( null, "", "/" + this.details.data.image_code );
			},
			detailsHide: function () {
				this.details.visible = false;
				window.history.pushState( null, "", "/" );
			},

			shareOnFacebook: function () {

			},
			shareOnTwitter : function () {

			},
		}
	} )

}