const { Fragment, useEffect, useState } = wp.element;
const { __ } = wp.i18n;
const { Button, Placeholder } = wp.components;
const { registerBlockType } = wp.blocks;

import H from "./components/Player";
import Controls from "./components/Controls";

registerBlockType("turbovideo-block/turbovideo-player", {
	title: __("Turbo Video"),
	icon: "video-alt3",
	category: "media",
	attributes: {
		clientId: {
			type: "string",
		},
		poster: {
			type: "string",
			default: "",
		},
		controls: {
			type: "object",
			default: {
				progress: true,
				"play-large": true,
				restart: false,
				rewind: true,
				play: true,
				"fast-forward": true,
				"current-time": true,
				duration: true,
				mute: true,
				volume: true,
				pip: true,
				airplay: true,
				settings: true,
				download: false,
				fullscreen: true,
			},
		},
		width: { type: "object", default: { number: 100, unit: "%" } },
		radius: {
			type: "object",
			default: { number: 0, unit: "px" },
		},
		autoplay: {
			type: "boolean",
			default: false,
		},
		muted: {
			type: "boolean",
			default: false,
		},
		download: {
			type: "boolean",
			default: false,
		},
		source: {
			type: "string",
		},
		videoURL: {
			type: "string",
		}
	},
	edit: ({ attributes, setAttributes, isSelected,  clientId }) => {
		const {
			source,
			poster,
			controls,
			repeat,
			muted,
			popup,
			autoHideControl,
			width,
			autoplay,
			download,
			videoURL,
		} = attributes;
		const [ player, setPlayer ] = useState( null );
		const [ inputValue, setInputValue ] = useState( '' );

		const videoSettings = {
			source: source,
			poster: poster,
			setSource: false,
		};

		const playerOptions = {
			controls: Object.keys( controls ).filter( ( key ) => controls[ key ] ),
			clickToPlay: false,
			autoplay: autoplay,
			muted: muted,
			download: download,
		};

		useEffect( () => {
			if ( videoURL ) {
				const videoElement = document.createElement( "video" );
				const playerContainer = document.querySelector( `#block-${ clientId } .h5vp_player` );
				const videoWrapper = document.querySelector(
					`#block-${ clientId } .h5vp_player .videoWrapper`
				);
				videoWrapper.innerHTML = "";
				videoElement.setAttribute( "id", "player" );
				videoElement.setAttribute( "data-poster", poster );

				videoWrapper.appendChild( videoElement );

				const videoPlayer = document.getElementById("player");

				const player = new Plyr(videoPlayer, playerOptions);
				setPlayer(player);
				if (muted) {
					player.muted = true;
					player.volume = 0;
				} else {
					player.muted = false; // 同步按钮的muted状态到视频
					player.volume = 1;
				}
				if ( download ) {
					player.download = download;
				}
				window.player = player;
				if (videoSettings.setSource) {
					player.source = {
						type: "video",
						title: "Title",
						poster: videoSettings.poster,
					};
				}

				if (window.innerWidth < 992) {
					player.on( "enterfullscreen", () => {
						if (screen.orientation) {
							screen.orientation.lock("landscape");
						}
					});

					player.on( "exitfullscreen", () => {
						if (screen.orientation) {
							screen.orientation.lock("portrait");
						}
					});
				}
				player.on( "enterfullscreen", () => {
					videoWrapper.classList.add("fullscreen");
				});

				player.on( "exitfullscreen", () => {
					videoWrapper.classList.remove( "fullscreen" );
				});
				MEPlayer.init( {} );
				const mp4Player = new MEPlayer( videoPlayer );

				const regex = /^turbo\:\/\/\w+\.\w+\/\w+/gm;
				if ( regex.exec( videoURL ) === null ) {
					return;
				}
				mp4Player.load( videoURL, {} );

				return () => {
					mp4Player.destroy();
				};
			}
		}, [ videoURL, poster ] );

		useEffect( () => {
			if ( isSelected ) {
				wp.data
					.dispatch("core/edit-post")
					.openGeneralSidebar("edit-post/block");
			}
		}, [ isSelected ] );

		useEffect( () => {
			if ( player ) {
				player.muted = muted;
				player.volume = muted ? 0 : 1;
			}
		}, [ muted ] );
		return (
			<Fragment>
				<Controls attributes={ attributes } setAttributes={ setAttributes } />
				{ videoURL ? (
					<Fragment>
						<H classes="h5vp_player" attributes={ attributes } />
					</Fragment>
				) : (
					<Fragment>
						<Placeholder
							icon={
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="24"
									height="24"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
									strokeLinecap="round"
									strokeLinejoin="round"
									className="presto-block-icon"
								>
									<polygon points="23 7 16 12 23 17 23 7"></polygon>
									<rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
								</svg>
							}
							instructions={__(
								"Upload a video or paste/write video url to get started.",
								"h5vp"
							)}
							label={__("Turbo Video ", "h5vp")}
						>
							<div className="h5vpUrlInput">
								<input
									type="url"
									aria-label={ __("URL", "h5vp") }
									placeholder={ __("Paste or type a video URL", "h5vp") }
									onChange={ ( e ) => setInputValue( e.target.value ) }
									value={ inputValue }
								/>
								<Button
									label={__("Apply", "h5vp")}
									type="submit"
									onClick={(e) => {
										e.preventDefault();
										setAttributes( { videoURL: inputValue } );
										setInputValue( '' );
									} }
									isPrimary
								>
									Apply
								</Button>
							</div>
						</Placeholder>
					</Fragment>
				)}
			</Fragment>
		);
	},
	save: () => {
		return null;
	},
});
