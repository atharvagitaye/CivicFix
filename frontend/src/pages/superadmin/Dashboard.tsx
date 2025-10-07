"use client"

import React, { useState } from "react"
import Header from "@/components/ui/header"
import Footer from "@/components/ui/footer"
import {
  Card, CardContent, CardHeader, CardTitle, CardDescription
} from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs"
import {
  Table, TableRow, TableCell, TableBody
} from "@/components/ui/table"
import {
  ResponsiveContainer, AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip
} from "recharts"
import { CheckCircle, Clock, AlertTriangle } from "lucide-react"

// Example chart data
const chartData = [
  { date: "Aug 01", reported: 1, inprogress: 0, resolved: 1 },
  { date: "Aug 02", reported: 2, inprogress: 1, resolved: 0 },
  { date: "Aug 03", reported: 1, inprogress: 1, resolved: 2 },
  { date: "Aug 04", reported: 0, inprogress: 2, resolved: 1 },
  { date: "Aug 05", reported: 3, inprogress: 1, resolved: 0 },
  { date: "Aug 06", reported: 1, inprogress: 0, resolved: 3 },
  { date: "Aug 07", reported: 2, inprogress: 1, resolved: 2 },
]

// Example table data
const tableData = [
  { id: 1, description: "Pothole near Main Street", location: "Sector 5", status: "Reported" },
  { id: 2, description: "Streetlight not working", location: "Park Avenue", status: "In Progress" },
  { id: 3, description: "Garbage pile up", location: "Near Bus Stand", status: "Resolved" },
  { id: 4, description: "Manhole cover missing", location: "Sector 5", status: "Reported" },
  { id: 5, description: "Water leakage", location: "Sector 8", status: "Resolved" },
  { id: 6, description: "Illegal dumping", location: "Riverside", status: "In Progress" },
]

const statusColors: Record<string, string> = {
  Reported: "text-red-600 font-medium",
  "In Progress": "text-yellow-600 font-medium",
  Resolved: "text-green-600 font-medium",
}

const SuperAdminDashboard: React.FC = () => {
  const [filter, setFilter] = useState("all")

  const filteredData =
    filter === "all" ? tableData : tableData.filter((item) => {
      if (filter === "reported") return item.status === "Reported"
      if (filter === "inprogress") return item.status === "In Progress"
      if (filter === "resolved") return item.status === "Resolved"
      return true
    })

  return (
    <div className="min-h-screen bg-blue-100 flex flex-col">
      {/* ✅ Global Header */}
      <Header />

      <main className="flex-grow p-6">
        {/* Stats */}
        <section className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <Card className="bg-blue-50 border-blue-400">
            <CardHeader>
              <CardTitle>Total Issues</CardTitle>
              <CardDescription>All issues reported</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-2xl font-bold">{tableData.length}</p>
            </CardContent>
          </Card>
          <Card className="bg-red-100 border-red-400">
            <CardHeader>
              <CardTitle>Reported</CardTitle>
              <CardDescription>Pending review</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-2xl font-bold text-red-600">
                {tableData.filter(i => i.status === "Reported").length}
              </p>
            </CardContent>
          </Card>
          <Card className="bg-yellow-100 border-yellow-400">
            <CardHeader>
              <CardTitle>In Progress</CardTitle>
              <CardDescription>Being fixed</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-2xl font-bold text-yellow-600">
                {tableData.filter(i => i.status === "In Progress").length}
              </p>
            </CardContent>
          </Card>
          <Card className="bg-green-100 border-green-400">
            <CardHeader>
              <CardTitle>Resolved</CardTitle>
              <CardDescription>Successfully closed</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-2xl font-bold text-green-600">
                {tableData.filter(i => i.status === "Resolved").length}
              </p>
            </CardContent>
          </Card>
        </section>

        {/* Chart with Filters */}
        <Card className="mb-6">
          <CardHeader className="flex justify-between items-center">
            <CardTitle>Issues Trend</CardTitle>
            <Tabs defaultValue="7days">
              <TabsList>
                <TabsTrigger value="3months">Last 3 months</TabsTrigger>
                <TabsTrigger value="30days">Last 30 days</TabsTrigger>
                <TabsTrigger value="7days">Last 7 days</TabsTrigger>
              </TabsList>
            </Tabs>
          </CardHeader>
          <CardContent>
            <ResponsiveContainer width="100%" height={300}>
              <AreaChart data={chartData}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <defs>
                  <linearGradient id="reported" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#dc2626" stopOpacity={0.8}/>
                    <stop offset="95%" stopColor="#dc2626" stopOpacity={0}/>
                  </linearGradient>
                  <linearGradient id="inprogress" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#ca8a04" stopOpacity={0.8}/>
                    <stop offset="95%" stopColor="#ca8a04" stopOpacity={0}/>
                  </linearGradient>
                  <linearGradient id="resolved" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#16a34a" stopOpacity={0.8}/>
                    <stop offset="95%" stopColor="#16a34a" stopOpacity={0}/>
                  </linearGradient>
                </defs>
                <Area type="monotone" dataKey="reported" stroke="#dc2626" fill="url(#reported)" />
                <Area type="monotone" dataKey="inprogress" stroke="#ca8a04" fill="url(#inprogress)" />
                <Area type="monotone" dataKey="resolved" stroke="#16a34a" fill="url(#resolved)" />
              </AreaChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        {/* Tabbed Table Section */}
        <Tabs defaultValue="all" onValueChange={setFilter}>
          <TabsList className="mb-4">
            <TabsTrigger value="all">All Issues</TabsTrigger>
            <TabsTrigger value="reported">Reported</TabsTrigger>
            <TabsTrigger value="inprogress">In Progress</TabsTrigger>
            <TabsTrigger value="resolved">Resolved</TabsTrigger>
          </TabsList>

          <TabsContent value={filter}>
            <Card>
              <div className="overflow-x-auto">
                <div className="max-h-[400px] overflow-y-auto">
                  <Table className="w-full border-collapse table-fixed">
                    <thead className="sticky top-0 bg-gray-100 z-10">
                      <TableRow>
                        <TableCell className="font-bold w-16">ID</TableCell>
                        <TableCell className="font-bold w-[200px]">Description</TableCell>
                        <TableCell className="font-bold w-[200px]">Location</TableCell>
                        <TableCell className="font-bold w-[150px]">Status</TableCell>
                      </TableRow>
                    </thead>
                    <TableBody>
                      {filteredData.map((item) => (
                        <TableRow key={item.id}>
                          <TableCell>{item.id}</TableCell>
                          <TableCell className="whitespace-normal">
                            {item.description}
                          </TableCell>
                          <TableCell className="truncate">{item.location}</TableCell>
                          <TableCell className={statusColors[item.status]}>
                            {item.status === "Resolved" ? (
                              <span className="flex items-center gap-1">
                                <CheckCircle className="w-4 h-4 text-green-600" /> Done
                              </span>
                            ) : item.status === "In Progress" ? (
                              <span className="flex items-center gap-1">
                                <Clock className="w-4 h-4 text-yellow-600" /> In Progress
                              </span>
                            ) : (
                              <span className="flex items-center gap-1">
                                <AlertTriangle className="w-4 h-4 text-red-600" /> Reported
                              </span>
                            )}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </div>
            </Card>
          </TabsContent>
        </Tabs>
      </main>

      {/* ✅ Global Footer */}
      <Footer />
    </div>
  )
}

export default SuperAdminDashboard
