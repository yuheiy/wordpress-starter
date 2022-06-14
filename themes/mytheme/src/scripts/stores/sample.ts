export default () => ({
	init() {
		if (process.env.NODE_ENV !== "production") {
			console.log("init store");
		}
	},
});
