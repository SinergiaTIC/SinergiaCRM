import { BASE_URL, INSTANCE_USER, INSTANCE_PASSWORD } from "#settings";

interface ApiConfig {
  endpoint?: string;
  username?: string;
  password?: string;
}

interface ApiError {
  number: string;
  name: string;
  description: string;
}

/* eslint-disable @typescript-eslint/no-explicit-any */

interface ApiResponse {
  id?: string;
  error?: ApiError;
  entry_list?: Array<{
    id: string;
    module_name: string;
    name_value_list: Record<string, { name: string; value: string }>;
  }>;
}

export class SuiteCRMApi {
  private session: string | null = null;
  private endpoint: string;
  private username: string;
  private password: string;

  constructor(config: ApiConfig = {}) {
    this.endpoint = config.endpoint ?? `${BASE_URL}service/v4_1/rest.php`;
    this.username = config.username ?? INSTANCE_USER;
    this.password = config.password ?? INSTANCE_PASSWORD;
  }

  async getEntry(module: string, id: string): Promise<ApiResponse> {
    this.#ensureSession();
    const data = await this.#request("get_entry", {
      session: this.session,
      module_name: module,
      id,
      select_fields: [],
      link_name_to_fields_array: [],
    });
    if (data.error) {
      throw new Error(`API getEntry failed for ${module}#${id}: ${data.error.name} — ${data.error.description}`);
    }
    return data;
  }

  async login(): Promise<string> {
    const data = await this.#request("login", {
      user_auth: {
        user_name: this.username,
        password: this.password,
        encryption: "PLAIN",
      },
      application_name: "Playwright",
    });
    if (data.error) {
      throw new Error(`API login failed: ${data.error.name} — ${data.error.description}`);
    }
    if (!data.id) {
      throw new Error(`API login failed: no session ID returned`);
    }
    this.session = data.id;
    return data.id;
  }

  async createEntry(module: string, fields: Record<string, string>): Promise<string> {
    this.#ensureSession();
    const data = await this.#request("set_entry", {
      session: this.session,
      module_name: module,
      name_value_list: fields,
    });
    if (data.error) {
      throw new Error(`API createEntry failed for ${module}: ${data.error.name} — ${data.error.description}`);
    }
    if (!data.id) {
      throw new Error(`API createEntry failed for ${module}: no record ID returned`);
    }
    return data.id;
  }

  async updateEntry(module: string, id: string, fields: Record<string, string>): Promise<void> {
    this.#ensureSession();
    const data = await this.#request("set_entry", {
      session: this.session,
      module_name: module,
      name_value_list: { ...fields, id },
    });
    if (data.error) {
      throw new Error(`API updateEntry failed for ${module}#${id}: ${data.error.name} — ${data.error.description}`);
    }
  }

  async deleteEntry(module: string, id: string): Promise<void> {
    this.#ensureSession();
    const data = await this.#request("set_entry", {
      session: this.session,
      module_name: module,
      name_value_list: { id, deleted: "1" },
    });
    if (data.error) {
      console.warn(
        `[SuiteCRMApi] Failed to delete ${module}#${id}: ${data.error.name} — ${data.error.description}`,
      );
    }
  }

  async deleteEntries(module: string, ids: string[]): Promise<void> {
    for (const id of ids) {
      await this.deleteEntry(module, id);
    }
  }

  get isLoggedIn(): boolean {
    return this.session !== null;
  }

  #ensureSession(): void {
    if (!this.session) {
      throw new Error("SuiteCRMApi not logged in. Call login() before making API calls.");
    }
  }

  async #request(method: string, restData: unknown): Promise<ApiResponse> {
    const body = new URLSearchParams({
      method,
      input_type: "JSON",
      response_type: "JSON",
      rest_data: JSON.stringify(restData),
    });

    const response = await fetch(this.endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body.toString(),
    });

    if (!response.ok) {
      throw new Error(
        `API request "${method}" failed: HTTP ${response.status} ${response.statusText}`,
      );
    }

    const json: ApiResponse = await response.json();
    return json;
  }
}
