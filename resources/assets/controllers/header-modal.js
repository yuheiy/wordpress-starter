import afterFrame from "afterframe";
import { Controller } from "stimulus";

const firstFrame = new Promise((resolve) => afterFrame(resolve));

export default class extends Controller {
	static targets = ["outside", "root", "firstFocus"];
	static classes = ["ready"];

	connect() {
		this.setReady();
	}

	async setReady() {
		// ページ読み込み時のチラつきを防止
		await firstFrame;
		this.element.classList.add(this.readyClass);
	}

	open() {
		if (this.isOpen) {
			return;
		}

		this.activate();
		lockBodyScroll();
	}

	close() {
		if (!this.isOpen) {
			return;
		}

		this.deactivate();
		unlockBodyScroll();
	}

	closeIfEscapeKeyIsPressed(event) {
		if (!this.isOpen) {
			return;
		}

		if (event.key === "Escape") {
			this.close();
		}
	}

	get isOpen() {
		return !this.rootTarget.inert;
	}

	activate() {
		this.lastFocus = document.activeElement;
		this.outsideTarget.inert = true;
		this.rootTarget.inert = false;
		this.firstFocusTarget.focus();
	}

	deactivate() {
		this.outsideTarget.inert = false;
		this.rootTarget.inert = true;
		this.lastFocus.focus();
		this.lastFocus = null;
	}
}

function lockBodyScroll() {
	document.body.style.overflow = "hidden";
}

function unlockBodyScroll() {
	document.body.style.overflow = "";
}
