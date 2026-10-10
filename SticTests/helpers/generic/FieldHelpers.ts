import { type Page } from "@playwright/test";

// WARN: fillRelateField uses page.evaluate() + querySelectorAll with a _ida$ name
// convention. If the SuiteCRM relate-IDA naming changes, the hidden input is silently
// not updated and the test passes with stale data.
export async function fillRelateField(
  page: Page,
  fieldLabel: string,
  searchValue: string,
  recordId: string,
): Promise<void> {
  await page.getByLabel(fieldLabel).fill(searchValue);
  await page.evaluate((id: string) => {
    const inputs = document.querySelectorAll('input[type="hidden"]');
    for (const input of inputs) {
      if ((input as HTMLInputElement).name.match(/_ida$/)) {
        (input as HTMLInputElement).value = id;
        input.dispatchEvent(new Event("change", { bubbles: true }));
        break;
      }
    }
  }, recordId);
}

export async function setDateTimeCombo(
  page: Page,
  fieldName: string,
  dateStr: string,
  hour: string,
  minute: string,
): Promise<void> {
  // TODO: implement DateTimeCombo widget interaction
}
