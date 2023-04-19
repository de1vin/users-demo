import React, { useState, useEffect } from "react";
import NavBar from "./components/Navbar";
import { Home } from "./components/Home";
import {
  routes as adminRoutes,
  AdminUsersList
} from "./components/Admin/Users";
import {
  createBrowserRouter,
  RouterProvider,
  Navigate
} from "react-router-dom";
import Container from "react-bootstrap/Container";

const router = createBrowserRouter([
  {
    path: "/",
    element: <Home/>,
  },
  // {
  //   path: "/admin/users/list",
  //   element: <AdminUsersList/>,
  // },
  ...adminRoutes
]);

function App() {
  return (
    <div>
      <NavBar/>
      <Container>
        <RouterProvider router={router} />
      </Container>
    </div>
  );
}

export default App;
