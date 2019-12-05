/**
** CONFIGURAÇÃÕES PARA O NOTIFICATION
**/
/**
** DEPENDENCIAS


	# Node Versão 8
	curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
	sudo apt-get install -y nodejs

	# NPM ultima versão
	npm install npm@latest -g

	# verificar Node e npm instalado,
	node -v
	npm -v

	# Instalação Gulp
	# Gulp
	sudo npm install gulp-cli -g
	sudo npm install gulp -D

	# Dependencias
	# gulp-uglify-es
	npm install --save-dev gulp-uglify-es

	# gulp-rename
	npm i gulp-rename

	# gulp-concat
	npm install --save-dev gulp-concat

	# gul-sass
	npm install gulp-sass --save-dev
	'Se der problema com o Sass, execute isso'
	npm rebuild node-sass

	# gulp-notify
	npm i gulp-notify

	# gulp-sourcemaps
	npm i gulp-sourcemaps


	# ERRO ESCUTA GULP
	gulp watch fails with error: Error: watch ... ENOSPC

	( SOLUÇÃO )
	- no terminal -
	echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf
**/

const projeto = 'DevNux',
		msg		 = 'O arquivo "<%= file.relative %>" foi compilado com sucesso!';

let template;

var gulp = require('gulp'),
	connect = require('gulp-connect-php'),
	browserSync = require('browser-sync'),
	sass	 = require('gulp-sass'),
	reload = browserSync.reload,
	uglify = require('gulp-uglify-es').default,
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	notify = require('gulp-notify'),
  fs      = require('fs'),
	sourcemaps = require('gulp-sourcemaps');

var package = fs.readFileSync('package.json', 'utf8');

const contate_site = [
	'js/js/site/PushHistory.js',
	'js/js/site/smoothScroll.js',
];

/**
** FUNÇÕES
**/

gulp.task('site_js', function(cb){

	// Função compila o SITE.JS com Map para Debugar
	return gulp.src(contate_site)
		.pipe(sourcemaps.init())
		.pipe(concat('site.min.js'))
		.pipe(sourcemaps.write('./map'))
		.pipe(gulp.dest('js'))
		.pipe(reload({ stream:true }))
		.on('error', function(err) {
			/// notify().write(err);
			done(erro);
		})
	//.pipe(notify({ title:projeto+' - Desenvolvimento', message: msg}))
});

gulp.task('dev_js', function(cb){

	// Função compila o SITE.JS com Map para Debugar
	return gulp.src('js/js/dev/dev.js')
		.pipe(sourcemaps.init())
		.pipe(concat('dev.min.js'))
		.pipe(sourcemaps.write('./map'))
		.pipe(gulp.dest('js'))
		.pipe(reload({ stream:true }))
		.on('error', function(err) {
			/// notify().write(err);
			done(erro);
		})
	//.pipe(notify({ title:projeto+' - Desenvolvimento', message: msg}))
});


gulp.task('site_js_producao', function(cb){
	// Função compila o JS com Map para Debugar
	return gulp.src(contate_site)
		.pipe(uglify())
		.pipe(concat('site.min.js'))
		.pipe(gulp.dest('js/'))
		.on('error', function(err) {
		})
});

gulp.task('dev_js_producao', function(cb){
	// Função compila o JS com Map para Debugar
	return gulp.src('js/js/dev/dev.js')
		.pipe(uglify())
		.pipe(concat('dev.min.js'))
		.pipe(gulp.dest('js/'))
		.on('error', function(err) {
		})
});

gulp.task('scss', function(){

  // Função compila o SCSS com Map para Debugar
  var sassFiles = 'css/scss/main.scss',
      cssDest = 'css';
   return gulp.src(sassFiles)
      .pipe(sourcemaps.init())
      .pipe(sass({outputStyle: 'compiled'}))
      .pipe(rename('site.min.css'))
      .pipe(sourcemaps.write('./map'))
      .pipe(gulp.dest(cssDest))
      .pipe(reload({ stream:true }))
      .on('error', function(err) {
         /// notify().write(err);
          done(erro); 
      })
});

gulp.task('scss_producao', function(){

  // Função compila o SCSS com Map para Debugar
  var sassFiles = 'css/scss/main.scss',
      cssDest = 'css';
   return gulp.src(sassFiles)
      .pipe(sourcemaps.init())
      .pipe(sass({outputStyle: 'compressed'}))
      .pipe(rename('site.min.css'))
      .pipe(gulp.dest(cssDest))
      .pipe(reload({ stream:true }))
      .on('error', function(err) {
         /// notify().write(err);
          done(erro); 
      })
});

gulp.task('default', function() {
    gulp.watch(['css/scss/**/*.scss'], gulp.series('scss'));
});

gulp.task('prod', function() {
 	 gulp.watch(['css/scss/**/*.scss'], gulp.series('scss_producao'));
	gulp.watch('js/js/site/*.js', gulp.series('site_js_producao'));
	gulp.watch('js/js/dev/*.js', gulp.series('dev_js_producao'));
});

gulp.task('dev', function() {

  connect.server({}, function (){
    browserSync.init({
      proxy: 'http://mvc2.local:80/'
    });
  });

  /* CSS */
  gulp.watch(['css/scss/**/*.scss'], gulp.series('scss'));

  /* JS */
	gulp.watch('js/js/site/*.js', gulp.series('site_js'));
	gulp.watch('js/js/dev/*.js', gulp.series('dev_js'));
});