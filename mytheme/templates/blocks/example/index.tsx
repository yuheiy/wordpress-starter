import { useBlockProps } from "@wordpress/block-editor";
import { registerBlockType } from "@wordpress/blocks";

const blockStyle = {
	backgroundColor: "#900",
	color: "#fff",
	padding: "20px",
};

registerBlockType("mytheme/example", {
	apiVersion: 2,
	title: "Example",
	icon: "universal-access-alt",
	category: "design",
	example: {},
	edit() {
		const blockProps = useBlockProps({ style: blockStyle });

		return <div {...blockProps}>Hello World (from the editor).</div>;
	},
	save() {
		const blockProps = useBlockProps.save({ style: blockStyle });

		return <div {...blockProps}>Hello World (from the frontend).</div>;
	},
});
