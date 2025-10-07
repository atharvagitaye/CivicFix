import React, { useEffect, useState } from "react";

interface Issue {
  id: number;
  description: string;
  location: string;
  status: "Reported" | "In Progress" | "Done";
  email: string;
  created_at: string;
}

const Issues: React.FC = () => {
  const [issues, setIssues] = useState<Issue[]>([]);

  // For now using static data, later replace with API fetch
  useEffect(() => {
    const dummyData: Issue[] = [
      {
        id: 1,
        description: "Pothole near Main Street",
        location: "Sector 5",
        status: "Reported",
        email: "ankita@example.com",
        created_at: "2025-09-30 10:00 AM",
      },
      {
        id: 2,
        description: "Streetlight not working",
        location: "Park Avenue",
        status: "In Progress",
        email: "rahul@example.com",
        created_at: "2025-09-30 11:30 AM",
      },
      {
        id: 3,
        description: "Garbage pile up",
        location: "Near Bus Stand",
        status: "Done",
        email: "meena@example.com",
        created_at: "2025-09-30 01:15 PM",
      },
    ];
    setIssues(dummyData);
  }, []);

  const getStatusStyle = (status: string) => {
    switch (status) {
      case "Reported":
        return "text-red-600 font-semibold flex items-center gap-1";
      case "In Progress":
        return "text-yellow-600 font-semibold flex items-center gap-1";
      case "Done":
        return "text-green-600 font-semibold flex items-center gap-1";
      default:
        return "";
    }
  };

  return (
    <div className="p-6 bg-blue-100 min-h-screen">
      <h1 className="text-2xl text-blue-500 font-bold mb-6">All Reported Issues</h1>

      <div className="bg-white rounded-lg shadow-md overflow-hidden">
        <table className="min-w-full border-collapse">
          <thead>
            <tr className="bg-gray-200 text-left">
              <th className="p-3 border">ID</th>
              <th className="p-3 border">Description</th>
              <th className="p-3 border">Location</th>
              <th className="p-3 border">Status</th>
              <th className="p-3 border">User Email</th>
              <th className="p-3 border">Reported At</th>
            </tr>
          </thead>
          <tbody>
            {issues.map((issue) => (
              <tr key={issue.id} className="hover:bg-gray-100">
                <td className="p-3 border">{issue.id}</td>
                <td className="p-3 border">{issue.description}</td>
                <td className="p-3 border">{issue.location}</td>
                <td className={`p-3 border ${getStatusStyle(issue.status)}`}>
                  {issue.status === "Reported" && "⚠️"}
                  {issue.status === "In Progress" && "⏳"}
                  {issue.status === "Done" && "✅"}
                  {issue.status}
                </td>
                <td className="p-3 border">{issue.email}</td>
                <td className="p-3 border">{issue.created_at}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default Issues;
