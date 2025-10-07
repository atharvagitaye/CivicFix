import React, { useEffect, useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";

const API_BASE = "http://127.0.0.1:8000/api"; // backend URL

const StaffDashboard: React.FC = () => {
  const [staff, setStaff] = useState<any>(null);
  const [assignedIssues, setAssignedIssues] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  const token = localStorage.getItem("token");

  // Fetch staff profile & assigned issues
  useEffect(() => {
    if (!token) return;

    const fetchData = async () => {
      try {
        const profileRes = await fetch(`${API_BASE}/user/profile`, {
          headers: { Authorization: `Bearer ${token}` },
          credentials: "include",
        });
        const staffData = await profileRes.json();
        setStaff(staffData);

        const issuesRes = await fetch(`${API_BASE}/issues?user_id=${staffData.id}`, {
          headers: { Authorization: `Bearer ${token}` },
          credentials: "include",
        });
        const issuesData = await issuesRes.json();
        setAssignedIssues(issuesData.data || issuesData);
      } catch (err) {
        console.error("Error:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [token]);

  // Update issue status
  const handleStatusChange = async (issueId: number, status: string) => {
    try {
      const res = await fetch(`${API_BASE}/issues/${issueId}/status`, {
        method: "PATCH",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        credentials: "include",
        body: JSON.stringify({ status }),
      });

      if (res.ok) {
        alert("Status updated successfully!");
        setAssignedIssues((prev) =>
          prev.map((i) => (i.id === issueId ? { ...i, status } : i))
        );
      } else {
        const errData = await res.json();
        alert("Error: " + JSON.stringify(errData));
      }
    } catch (err) {
      console.error("Update failed:", err);
    }
  };

  if (loading) return <p className="text-center mt-10">Loading...</p>;

  return (
    <div className="p-6 space-y-6">
      {/* Staff Info */}
      <Card>
        <CardHeader>
          <CardTitle className="text-blue-700">Staff Profile</CardTitle>
        </CardHeader>
        <CardContent>
          {staff && (
            <div>
              <p><b>Name:</b> {staff.name}</p>
              <p><b>Email:</b> {staff.email}</p>
            </div>
          )}
        </CardContent>
      </Card>

      {/* Assigned Issues */}
      <Card>
        <CardHeader>
          <CardTitle className="text-blue-700">Assigned Issues</CardTitle>
        </CardHeader>
        <CardContent>
          {assignedIssues.length === 0 ? (
            <p className="text-gray-500">No issues assigned yet.</p>
          ) : (
            <div className="space-y-4">
              {assignedIssues.map((issue) => (
                <div key={issue.id} className="border p-3 rounded-md bg-gray-50 shadow-sm">
                  <p className="font-medium">{issue.description}</p>
                  <p className="text-sm text-gray-600">
                    Status: <span className="font-semibold">{issue.status}</span>
                  </p>
                  <p className="text-xs text-gray-400">
                    Assigned on {new Date(issue.created_at).toLocaleString()}
                  </p>

                  {/* Status Update Dropdown */}
                  <Select
                    onValueChange={(status) => handleStatusChange(issue.id, status)}
                    defaultValue={issue.status}
                  >
                    <SelectTrigger className="mt-2 w-[200px]">
                      <SelectValue placeholder="Update Status" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="created">Created</SelectItem>
                      <SelectItem value="under_process">Under Process</SelectItem>
                      <SelectItem value="resolved">Resolved</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
};

export default StaffDashboard;
