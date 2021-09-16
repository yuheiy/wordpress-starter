---
to: mytheme/assets/components/form/inc/utils.ts
---
/**
 * 半角数
 */
export const PAT_NUMBER = /^[0-9]+$/;

/**
 *
 * メールアドレス
 */
export const PAT_EMAIL =
	/^[0-9,A-Z,a-z][0-9,a-z,A-Z,_,\.,\-,\+]+@[0-9,A-Z,a-z][0-9,a-z,A-Z,\.,\-]+\.[a-z]+$/;

/**
 * 半角英
 */
export const PAT_ALPHABET = /^[a-zA-Z]+$/;

/**
 * 半角英数
 */
export const PAT_ALPHANUMERIC = /^[0-9A-Za-z]+$/;

/**
 * 全角文字 (半角以外)
 */
export const PAT_ZENKAKU = /^[^\x01-\x7E\xA1-\xDF]+$/;

/**
 * 全角カタカナ
 */
export const PAT_KATAKANA = /^[ァ-ヶー　]*$/;

/**
 * 全角ひらがな
 */
export const PAT_KANA = /^ぁ-ん/;

/**
 * 電話番号(xxx-xxxx-xxxx)
 */
export const PAT_PHONENUMBER_WITH_HYPHEN = /^\d{2,4}\-?\d{2,4}\-?\d{2,4}$/;

/**
 * 電話番号
 */
export const PAT_PHONENUMBER = /^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/;

/**
 * 郵便番号
 */
export const PAT_ZIPCODE = /^〒?(\s*?)(\d{7}|\d{3}-\d{4})$/;

export function between(
	value: string | number,
	from: number,
	to: number
): boolean {
	const len = String(value).length;
	return from <= len && len <= to;
}

export function minLength(value: string | number, min: number): boolean {
	const len = String(value).length;
	return Number(min) <= len;
}

export function maxLength(value: string | number, max: number): boolean {
	let len = String(value).length;
	return len <= Number(max);
}

export function pattern(value: string | number, pattern: RegExp) {
	return pattern.test(String(value));
}

export function isEmpty(
	value: string | number | any[] | {} | null | undefined
): boolean {
	if (typeof value === "string") {
		return "" === value;
	} else if (value instanceof Array) {
		return value.length === 0;
	} else if (value instanceof Object) {
		return Object.keys(value).length === 0;
	} else {
		return !value;
	}
}
