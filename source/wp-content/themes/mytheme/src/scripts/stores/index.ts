import { Alpine } from "alpinejs";
import camelCase from "camelcase";

export default function (Alpine: Alpine) {
	const context = (require as any).context("./", false, /\.ts$/);

	for (const key of context.keys()) {
		if (key === "./index.ts") continue;

		const [base] = key.split("/").reverse();
		const [name] = base.split(".");
		const module = context(key);
		Alpine.store(camelCase(name), module.default());
	}
}
