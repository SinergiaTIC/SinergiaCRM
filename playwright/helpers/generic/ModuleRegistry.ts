import type { ModuleKey, ModuleEntry } from "../../models/types.js";

export type { ModuleKey, ModuleEntry };

export const ALL_MODULES: ModuleEntry[] = [
  { key: "Accounts", type: "crm" },
  { key: "Contacts", type: "crm" },
  { key: "Opportunities", type: "crm" },
  { key: "Calls", type: "activity" },
  { key: "Meetings", type: "activity" },
  { key: "Tasks", type: "activity" },
  { key: "Cases", type: "crm" },
  { key: "Leads", type: "crm" },
  { key: "Prospects", type: "crm" },
  { key: "Campaigns", type: "crm" },
  { key: "Documents", type: "crm" },
  { key: "Notes", type: "crm" },
  { key: "Calendar", type: "crm" },
  { key: "Bugs", type: "crm" },
  { key: "Releases", type: "crm" },
  { key: "Project", type: "crm" },
  { key: "ProjectTask", type: "activity" },
];
