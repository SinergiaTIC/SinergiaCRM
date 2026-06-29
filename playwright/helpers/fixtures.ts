import { SuiteCRMApi } from "./api.js";

export interface FixtureRecord {
  module: string;
  id: string;
  name: string;
}

function tagFixtureName(module: string, fields: Record<string, string>): Record<string, string> {
  if (fields["name"] && !fields["name"].includes("__TEST__")) {
    return { ...fields, name: `__TEST__${module}_${fields["name"]}` };
  }
  return { ...fields };
}

export class FixtureManager {
  private api: SuiteCRMApi;
  private created: FixtureRecord[] = [];

  constructor(api: SuiteCRMApi) {
    this.api = api;
  }

  async createRecord(
    module: string,
    fields: Record<string, string>,
  ): Promise<FixtureRecord> {
    const tagged = tagFixtureName(module, fields);
    const id = await this.api.createEntry(module, tagged);
    const record: FixtureRecord = {
      module,
      id,
      name: tagged["name"] ?? "",
    };
    this.created.push(record);
    return record;
  }

  async updateRecord(
    module: string,
    id: string,
    fields: Record<string, string>,
  ): Promise<void> {
    await this.api.updateEntry(module, id, fields);
  }

  async cleanupAll(): Promise<void> {
    for (const record of [...this.created].reverse()) {
      try {
        await this.api.deleteEntry(record.module, record.id);
      } catch (err) {
        const msg = err instanceof Error ? err.message : String(err);
        console.warn(`[FixtureManager] Cleanup failed for ${record.module}#${record.id}: ${msg}`);
      }
    }
    this.created = [];
  }

  get createdRecords(): readonly FixtureRecord[] {
    return this.created;
  }
}
