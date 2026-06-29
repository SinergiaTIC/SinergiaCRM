export type ModuleKey =
  | "Accounts"
  | "Contacts"
  | "Opportunities"
  | "Calls"
  | "Meetings"
  | "Tasks"
  | "Cases"
  | "Leads"
  | "Targets"
  | "Prospects"
  | "Campaigns"
  | "Documents"
  | "Notes"
  | "Calendar"
  | "Bugs"
  | "Releases"
  | "Project"
  | "ProjectTask";

export type ModuleType = "crm" | "activity" | "readonly";

export interface ModuleEntry {
  key: ModuleKey;
  type: ModuleType;
}

export interface DeviceConfig {
  name: string;
  width: number;
  height: number;
}

export interface LabelSource {
  file: string;
}
