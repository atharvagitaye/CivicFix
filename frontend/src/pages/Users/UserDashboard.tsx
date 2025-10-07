// src/pages/Users/UserDashboard.tsx
import React from "react";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { Input } from "@/components/ui/input";

const mockUser = {
  name: "Ankita Pawar",
  email: "ankita@example.com",
};

const mockIssues = [
  { id: 1, title: "Pothole near main road", status: "Pending" },
  { id: 2, title: "Streetlight not working", status: "Resolved" },
];

const UserDashboard: React.FC = () => {
  return (
    <div className="min-h-screen bg-gray-100">
      {/* Top Header */}
      <header className="bg-black text-white p-4 shadow-md">
        <div className="container mx-auto flex justify-between items-center">
          <h1 className="text-2xl font-bold">CivicFix - User Dashboard</h1>
          <Button variant="destructive" className="bg-red-600 hover:bg-red-700">
            Logout
          </Button>
        </div>
      </header>

      {/* Main Content */}
      <main className="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Profile Section */}
        <Card className="shadow-lg">
          <CardHeader>
            <CardTitle className="text-xl font-semibold">My Profile</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <Input placeholder="Name" value={mockUser.name} readOnly />
            <Input placeholder="Email" value={mockUser.email} readOnly />
            <Button className="w-full bg-black text-white hover:bg-gray-800">
              Update Profile
            </Button>
          </CardContent>
        </Card>

        {/* My Issues Section */}
        <Card className="lg:col-span-2 shadow-lg">
          <CardHeader>
            <CardTitle className="text-xl font-semibold">My Issues</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            {mockIssues.map((issue) => (
              <div
                key={issue.id}
                className="p-3 border rounded-lg flex justify-between items-center bg-white shadow-sm"
              >
                <span className="font-medium">{issue.title}</span>
                <span
                  className={`text-sm px-2 py-1 rounded-full ${
                    issue.status === "Resolved"
                      ? "bg-green-200 text-green-800"
                      : "bg-yellow-200 text-yellow-800"
                  }`}
                >
                  {issue.status}
                </span>
              </div>
            ))}
            <Button className="w-full mt-3 bg-black text-white hover:bg-gray-800">
              Report New Issue
            </Button>
          </CardContent>
        </Card>

        {/* Feedback Section */}
        <Card className="lg:col-span-3 shadow-lg">
          <CardHeader>
            <CardTitle className="text-xl font-semibold">Feedback</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <Textarea placeholder="Write your feedback..." />
            <Button className="w-full bg-black text-white hover:bg-gray-800">
              Submit Feedback
            </Button>
          </CardContent>
        </Card>
      </main>
    </div>
  );
};

export default UserDashboard;
