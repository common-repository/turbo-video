import { createElement } from "react";

const PlayerWrapper = ({ classes = "", attributes }) => {
	const { width, radius, muted, autoplay } = attributes;
	const i = 0 === width.number ? "100%" : width.number + "%";
	const u = radius.number + radius.unit;
	return createElement(
		"div",
		{
			className: classes,
			id: "h5vp_player",
			"data-settings": JSON.stringify({}),
			style: {
				width: i,
				borderRadius: u,
				overflow: "hidden",
				margin: "0 auto",
			},
		},
		createElement( "div", {
			className: "videoWrapper",
		})
	);
};

export default PlayerWrapper;
