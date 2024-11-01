( () => {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function() {
		const h5vp = document.querySelectorAll( '.h5vp_player_initializer' );
		if ( h5vp ) {
			player.initialize( h5vp );
		}
	} );

	window.addEventListener( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/H5VPPlayer.default',
			function( obj, n) {
				player.initialize( obj );
			}
		);
	} );

	class player {
		static initialize(el) {
			let options,
				infos,
				isMobile =
					arguments.length > 1 && undefined !== arguments[1] && arguments[1],
				isEditor =
					arguments.length > 2 && undefined !== arguments[2] && arguments[2],
				screenWidth = window.innerWidth;

			const detectorData = this.getDetectorData( el, isMobile, isEditor );
			if ( ! detectorData ) {
				return false;
			}

			const {
				wrapper,
				mediaElement,
				options: playerOptions,
				infos: playerInfos,
			} = detectorData;

			if ( screenWidth < 400 ) {
				// playerOptions.controls = this.controlsForMobile(
				// 	playerOptions && playerOptions.controls
				// );
			}
			if ( ! playerOptions.download ) {
				playerOptions.controls = this.removeControl( playerOptions.controls, 'download' );
			}
			const player = new Plyr(mediaElement, playerOptions );
			if ( playerOptions.muted && playerOptions.autoplay ) {
				player.volume = 0;
			}

			window.player = player;

			if ( playerInfos && playerInfos.setSource ) {
				player.source = {
					type: 'video',
					title: 'Title',
					sources: [
						{
							src: playerInfos && playerInfos.source,
							type: 'video/mp4',
						},
					],
					poster: playerInfos && playerInfos.poster,
				};
			}

			// player.on( 'loadstart', function() {
			// loadingOverlay.style.display = 'flex';
			// } );

			if ( window.innerWidth < 992 ) {
				player.on( 'enterfullscreen', function() {
					if ( screen.orientation ) {
						screen.orientation.lock( 'landscape' );
					}
				} );

				player.on( 'exitfullscreen', function() {
					if ( screen.orientation ) {
						screen.orientation.lock( 'portrait' );
					}
				} );
			}
			player.on( 'enterfullscreen', function() {
				wrapper.classList.add( 'fullscreen' );
			} );

			player.on( 'exitfullscreen', function() {
				wrapper.classList.remove( 'fullscreen' );
			} );

			player.on( 'ready', function() {
				const playerDiv = document.querySelector( '.plyr__controls' );
				const aTag = playerDiv.querySelector( 'a.plyr__controls__item.plyr__control' );
				if ( aTag ) {
					mp4Player.loader.getDownloadLink().then( ( link ) => {
						if ( link ) {
							aTag.href = link;
						}
					} );
				}

				// window.addEventListener( 'resize', function() {
				// 	if ( screenWidth < 400 && ! player.fullscreen.active ) {
				// 	  // 隐藏播放按钮
				// 	  player.elements.buttons.download.style.display = 'none';
				// 	  // 隐藏音量控制
				// 	  player.elements.buttons.settings.style.display = 'none';
				// 	}

				// 	if ( player.fullscreen.active ) {
				// 		player.elements.buttons.download.style.display = '';
				// 		// 隐藏音量控制
				// 		player.elements.buttons.settings.style.display = '';
				// 	}
				// } );

				// if ( screenWidth < 768 ) {
				// 	player.elements.buttons.play.style.display = 'none';
				// 	// 隐藏音量控制
				// 	player.elements.buttons.mute.style.display = 'none';
				// }
			} );
			if (
				!playerOptions ||
				!playerOptions.controls ||
				!playerOptions.controls.includes("progress")
			) {
				const controls = wrapper.querySelector( '.plyr__controls' );
				if ( controls ) {
					controls.style.background = 'transparent';
				}
			}

			// if (typeof h5vp !== "undefined" && Boolean(parseInt(h5vp?.pauseOther))) {
			// 	player.on("play", function () {
			// 		if (location.pathname.includes("wp-admin")) return false;
			// 		const uniqueId = wrapper.dataset?.uniqueId;
			// 		const videos = document.querySelectorAll(
			// 			`video:not([data-unique-id="${uniqueId}"] video, a video)`
			// 		);
			// 		Object.values(videos).map(function (video) {
			// 			video.pause();
			// 		});
			// 	});
			// }
		}

		static getDetectorData(el, isMobile, isEditor) {
			if (el === null) return false;
			const self = this;
			if (el[0] !== undefined) {
				return el.forEach(function (elment) {
					self.initialize(elment, isMobile, isEditor);
				});
				return false;
			}

			if (el !== undefined && el.length === 0) return false;

			if (el.querySelector(".h5vp_player") !== null) {
				el = el.querySelector(".h5vp_player");
			}

			let settings = el.getAttribute("data-settings");

			let options, infos;

			if (settings) {
				settings = JSON.parse(settings);
				el.dataset.settings = "";
				if (!isMobile) {
					options = settings.options;
				}
				if (!isEditor) {
					infos = settings.infos;
				}
			}
			const mediaElement = el.querySelector("video");
			options.loading = true;

			return { wrapper: el, options, mediaElement, infos };
		}

		static controlsForMobile(controls) {
			controls = this.removeControl(controls, "restart");
			// controls = this.removeControl(controls, "duration");
			// controls = this.removeControl(controls, "mute");
			// controls = this.removeControl(controls, "volume");
			// controls = this.removeControl(controls, "fullscreen");
			controls = this.removeControl(controls, "settings");
			return this.removeControl(controls, "download");
		}
		static removeControl(controls, control) {
			const index = controls.indexOf(control);
			if (index !== -1) {
				controls.splice(index, 1);
			}
			return controls;
		}

		static addControl(controls, control) {
			controls.push(control);
			return controls;
		}

		static setHeight(element, player) {
			// const width = jQuery(element).width();
			// player.on("loadeddata", function () {
			// 	const ratio = player.ratio;
			// 	if (!ratio) return false;
			// 	const [widthRatio, heightRatio] = ratio.split(":");
			// 	const height = (width / widthRatio) * heightRatio;
			// 	element.classList.add("plyr_set_height");
			// 	jQuery(element)
			// 		.find(".plyr")
			// 		.css("height", height + "px");
			// });
			player.on("ready", function () {
				setTimeout(function () {
					var plyrElement = element.querySelector(".plyr");
					if (plyrElement) {
						plyrElement.removeAttribute("style");
					}
				}, 300);
			});
		}
	}
})();
