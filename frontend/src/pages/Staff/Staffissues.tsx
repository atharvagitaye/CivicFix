import React, { useEffect, useState } from "react";

interface Staff {
  id: number;
  name: string;
  email: string;
  assigned: number;
  resolved: number;
  pending: number;
}

const StaffIssues: React.FC = () => {
  const [staffData, setStaffData] = useState<Staff[]>([]);

  useEffect(() => {
    const dummyData: Staff[] = [
      { id: 1, name: "Ravi Kumar", email: "ravi@example.com", assigned: 12, resolved: 8, pending: 4 },
      { id: 2, name: "Sneha Sharma", email: "sneha@example.com", assigned: 10, resolved: 7, pending: 3 },
      { id: 3, name: "Arjun Patel", email: "arjun@example.com", assigned: 15, resolved: 12, pending: 3 },
    ];
    setStaffData(dummyData);
  }, []);

  return (
    <div className="p-6 bg-blue-100 min-h-screen">
      <h1 className="text-2xl text-blue-500 font-bold mb-6">Staff Issue Overview</h1>

      <div className="bg-white rounded-lg shadow-md overflow-x-auto">
        <table className="min-w-full border-collapse">
          <thead>
            <tr className="bg-gray-200 text-left">
              <th className="p-3 border">Staff ID</th>
              <th className="p-3 border">Name</th>
              <th className="p-3 border">Email</th>
              <th className="p-3 border">Assigned</th>
              <th className="p-3 border">Resolved</th>
              <th className="p-3 border">Pending</th>
              <th className="p-3 border">Progress</th>
            </tr>
          </thead>
          <tbody>
            {staffData.map((staff) => {
              const progress =
                staff.assigned > 0 ? Math.round((staff.resolved / staff.assigned) * 100) : 0;

              return (
                <tr key={staff.id} className="hover:bg-gray-100">
                  <td className="p-3 border">{staff.id}</td>
                  <td className="p-3 border">{staff.name}</td>
                  <td className="p-3 border">{staff.email}</td>
                  <td className="p-3 border">{staff.assigned}</td>
                  <td className="p-3 border text-green-600 font-semibold">✅ {staff.resolved}</td>
                  <td className="p-3 border text-yellow-600 font-semibold">⏳ {staff.pending}</td>
                  <td className="p-3 border">
                    <div className="w-full bg-gray-200 rounded-full h-3">
                      <div
                        className="bg-blue-500 h-3 rounded-full"
                        style={{ width: `${progress}%` }}
                      ></div>
                    </div>
                    <span className="text-sm text-gray-600">{progress}%</span>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default StaffIssues;
