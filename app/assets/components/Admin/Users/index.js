import AdminUsersList from "./List";
import React from "react";

const routes = [
  {
    path: "/admin/users",
    element: <AdminUsersList/>,
  },
];

export {
  routes,
  AdminUsersList
};
