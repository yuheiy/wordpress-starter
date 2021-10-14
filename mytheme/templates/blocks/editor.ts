importAll(
	(require as any).context("../../assets/scss/blocks", true, /\/editor\.scss$/)
);
importAll((require as any).context(".", true, /\/index\.tsx?$/));

function importAll(r: any) {
	r.keys().forEach(r);
}
