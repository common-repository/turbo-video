const { __ } = wp.i18n;
const { TabPanel, Panel, PanelBody, PanelRow, FormToggle } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { getCurrentPostId, getCurrentPostType } = wp.data.select("core/editor");

const VideoPlayerSettings = (props) => {
	const { attributes, setAttributes } = props;
	const {
		source,
		poster,
		repeat,
		radius,
		autoplay,
		muted,
		width,
		autoHideControl,
		resetOnEnd,
	} = attributes;
	const postId = getCurrentPostId();
	return (
		<InspectorControls style={{ marginBottom: "40px" }}>
			{getCurrentPostType() === "videoplayer" && (
				<Panel>
					<PanelBody>
						<PanelRow>
							<p style={{ fontSize: "16px", lineHeight: "135%", color: "red" }}>
								<a
									target="_blank"
									href="edit.php?post_type=videoplayer&page=settings"
								>
									{__("Click Here", "pdfp")}
								</a>
								{__(
									" to disable Gutenberg shortcode generator ( to back old generator)",
									"pdfp"
								)}
							</p>
						</PanelRow>
					</PanelBody>
				</Panel>
			)}
			<Panel>
				<PanelBody>
					<div className="h5vp_front_shortcode">
						<input value={`[html5_video id=${postId}]`} />
						<span className="htooltip">{__("Copy To Clipboard", "h5vp")}</span>
					</div>
				</PanelBody>
			</Panel>
			<TabPanel
				className="bPSS"
				activeClass="active-tab"
				tabs={[
					{ name: "settings", title: "Settings", className: "general btTab" },
					{ name: "controls", title: "Controls", className: "slider btTab" },
					{ name: "style", title: "Style", className: "style btTab" },
				]}
			>
				{(tab) => (
					<span>
						{tab.name === "settings" && (
							<span>
								<Panel>
									<PanelBody>
										<PanelRow>
											<FormToggle
												id="repeat"
												checked={repeat}
												onChange={() => setAttributes({ repeat: !repeat })}
											>
												{__("Repeat", "h5vp")}
											</FormToggle>
											<FormToggle
												id="autoplay"
												checked={autoplay}
												onChange={() => setAttributes({ autoplay: !autoplay })}
											>
												{__("Autoplay", "h5vp")}
											</FormToggle>
											<FormToggle
												id="muted"
												checked={muted}
												onChange={() => setAttributes({ muted: !muted })}
											>
												{__("Muted", "h5vp")}
											</FormToggle>
											{!repeat && (
												<FormToggle
													id="resetOnEnd"
													checked={resetOnEnd}
													onChange={() =>
														setAttributes({ resetOnEnd: !resetOnEnd })
													}
												>
													{__("Reset On End", "h5vp")}
												</FormToggle>
											)}
											<FormToggle
												id="autoHideControl"
												checked={autoHideControl}
												onChange={() =>
													setAttributes({ autoHideControl: !autoHideControl })
												}
											>
												{__("Auto Hide Control", "h5vp")}
											</FormToggle>
										</PanelRow>
									</PanelBody>
								</Panel>
							</span>
						)}
						{tab.name === "controls" && (
							<p attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "style" && (
							<span>
								<Panel>
									<PanelBody>
										<PanelRow>
											<h
												label={("Width", "h5vp")}
												enable={{ eDevice: false }}
												value={width.number}
												unit={width.unit}
												max={1000}
												onChange={(value) =>
													setAttributes({ width: { ...width, number: value } })
												}
												onChangeUnit={(unit) =>
													setAttributes({ width: { number: 0, unit: unit } })
												}
											/>
											<h
												label={("Round Corner", "h5vp")}
												enable={{ eDevice: false }}
												value={radius.number}
												unit={radius.unit}
												max={100}
												onChange={(value) =>
													setAttributes({
														radius: { ...radius, number: value },
													})
												}
												onChangeUnit={(unit) =>
													setAttributes({ radius: { number: 0, unit: unit } })
												}
											/>
										</PanelRow>
									</PanelBody>
								</Panel>
							</span>
						)}
					</span>
				)}
			</TabPanel>
		</InspectorControls>
	);
};

export default VideoPlayerSettings;
