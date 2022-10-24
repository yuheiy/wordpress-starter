import { Alpine } from "alpinejs";
import camelCase from "camelcase";

export default function (Alpine: Alpine) {
	const context = (require as any).context("./", false, /\.ts$/);

	for (const key of context.keys()) {
		if (key === "./index.ts") continue;

		const base = key.split("/").at(-1);
		const name = base.split(".").slice(0, -1).join(".");
		const module = context(key);
		Alpine.store(camelCase(name), module.default());
	}
}
