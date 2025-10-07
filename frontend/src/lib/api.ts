// API configuration for the CivicFix frontend

export const API_BASE_URL = "http://127.0.0.1:8000/api";

// Default fetch options for API calls
export const defaultFetchOptions: RequestInit = {
  credentials: "include",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
  },
};

// Helper function to make authenticated API calls
export const apiCall = async (
  endpoint: string, 
  options: RequestInit = {}
): Promise<Response> => {
  const token = localStorage.getItem("token");
  
  const headers = {
    ...defaultFetchOptions.headers,
    ...options.headers,
  };

  if (token) {
    (headers as any)["Authorization"] = `Bearer ${token}`;
  }

  return fetch(`${API_BASE_URL}${endpoint}`, {
    ...defaultFetchOptions,
    ...options,
    headers,
  });
};

// Specific API methods
export const api = {
  // Authentication
  register: (userData: { name: string; email: string; password: string; role: string }) =>
    apiCall("/register", {
      method: "POST",
      body: JSON.stringify(userData),
    }),

  login: (credentials: { email: string; password: string }) =>
    apiCall("/login", {
      method: "POST",
      body: JSON.stringify(credentials),
    }),

  // User profile
  getUserProfile: () => apiCall("/user/profile"),

  // Issues
  getIssues: (userId?: number) => {
    const params = userId ? `?user_id=${userId}` : "";
    return apiCall(`/issues${params}`);
  },

  updateIssueStatus: (issueId: number, status: string) =>
    apiCall(`/issues/${issueId}/status`, {
      method: "PATCH",
      body: JSON.stringify({ status }),
    }),
};