import afterFrame from "afterframe";
import { Controller } from "stimulus";
import invariant from "tiny-invariant";

const firstFrame = new Promise((resolve) => afterFrame(resolve));

export default class extends Controller {
  static targets = ["outside", "root", "firstFocus"];
  static classes = ["ready"];
  readonly outsideTarget!: HTMLElement;
  readonly rootTarget!: HTMLElement;
  readonly firstFocusTarget!: HTMLElement;
  readonly readyClass!: string;
  lastFocus: Element | null = null;

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

  closeIfEscapeKeyIsPressed(event: unknown) {
    invariant(event instanceof KeyboardEvent);

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
    invariant(this.lastFocus instanceof HTMLElement);
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
