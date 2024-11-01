import { Fragment } from "react";

const Player = ({ attributes }) => {
	const {
		source = "",
		poster = "",
		preload = "metadata",
		autoplay,
		muted,
	} = attributes;

	return (
		<div>
			<Fragment>
				<video
					controls
					playsInline
					data-poster={ poster }
					preload={ preload }
					autoPlay={ autoplay }
					muted={ muted }
				>
					Your browser does not support the video tag.
				</video>
			</Fragment>
		</div>
	);
};

export default Player;
