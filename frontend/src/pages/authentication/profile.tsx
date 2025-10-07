import React, { useState } from "react";

const Profile: React.FC = () => {
  const [profileImage, setProfileImage] = useState<string | null>(null);

  // Handle file upload
  const handleImageChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    if (event.target.files && event.target.files[0]) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onloadend = () => {
        setProfileImage(reader.result as string);
      };
      reader.readAsDataURL(file);
    }
  };

  return (
    <div className="p-6 bg-blue-100 min-h-screen flex justify-center items-start">
      <div className="bg-white rounded-lg shadow-md w-full max-w-3xl p-6">
        <h1 className="text-2xl font-bold text-blue-600 mb-6">Profile</h1>

        {/* Profile Picture */}
        <div className="flex flex-col items-center mb-6">
          <div className="relative">
            <img
              src={profileImage || "https://via.placeholder.com/150"}
              alt="Profile"
              className="w-32 h-32 rounded-full object-cover border-4 border-blue-500"
            />
            <input
              type="file"
              accept="image/*"
              onChange={handleImageChange}
              className="absolute inset-0 opacity-0 cursor-pointer"
              title="Change Profile Picture"
            />
          </div>
          <p className="text-sm text-gray-600 mt-2">Click on the picture to upload new</p>
        </div>

        {/* Personal Information */}
        <div className="mb-6">
          <h2 className="text-lg font-semibold mb-3 text-gray-700">Personal Info</h2>
          <form className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input
              type="text"
              placeholder="Full Name"
              className="p-2 border rounded-md"
            />
            <input
              type="email"
              placeholder="Email"
              className="p-2 border rounded-md"
            />
            <input
              type="text"
              placeholder="Phone Number"
              className="p-2 border rounded-md"
            />
            <input
              type="text"
              placeholder="Role (Admin/Staff/User)"
              className="p-2 border rounded-md"
              disabled
            />
            <button
              type="submit"
              className="col-span-1 md:col-span-2 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600"
            >
              Save Changes
            </button>
          </form>
        </div>

        {/* Change Password */}
        <div className="mb-6">
          <h2 className="text-lg font-semibold mb-3 text-gray-700">Change Password</h2>
          <form className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input
              type="password"
              placeholder="Current Password"
              className="p-2 border rounded-md"
            />
            <input
              type="password"
              placeholder="New Password"
              className="p-2 border rounded-md"
            />
            <input
              type="password"
              placeholder="Confirm New Password"
              className="p-2 border rounded-md"
            />
            <button
              type="submit"
              className="col-span-1 md:col-span-2 bg-green-500 text-white py-2 rounded-md hover:bg-green-600"
            >
              Update Password
            </button>
          </form>
        </div>

        {/* History Section */}
        <div>
          <h2 className="text-lg font-semibold mb-3 text-gray-700">Activity History</h2>
          <div className="bg-gray-100 rounded-md p-4 max-h-48 overflow-y-auto">
            <ul className="space-y-2 text-sm text-gray-700">
              <li>✅ Issue "Streetlight not working" resolved on 2025-09-29</li>
              <li>⚠️ Issue "Pothole near Main Street" reported on 2025-09-28</li>
              <li>⏳ Assigned task "Garbage pile up" still pending</li>
              <li>✅ Password updated successfully on 2025-09-20</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Profile;
