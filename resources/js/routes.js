import {createBrowserRouter} from "react-router-dom"
import {Login} from "./pages/Login"
import {Dashboard} from "./pages/Dashboard"
import {Categories} from "./pages/content/Category"
import {Users} from "./pages/management/User"
import {User} from "./pages/management/User/User"
import {Roles} from "./pages/management/Role"
import {Permissions} from "./pages/management/Permission"
import {Category} from "./pages/content/Category/Category";
import {Article} from "./pages/content/Article/Article";
import {Protected} from "./components/Protected";

const routes = createBrowserRouter([
  {
    path: "/",
    element: <Protected />,
    children: [
      { path: "/", element: <Dashboard /> },
      { path: "/content/categories", element: <Categories /> },
      { path: "/content/categories/:category", element: <Category /> },
      { path: "/content/article/:article", element: <Article /> },
      { path: "/management/users", element: <Users /> },
      { path: "/management/users/:user", element: <User /> },
      { path: "/management/roles", element: <Roles /> },
      { path: "/management/permissions", element: <Permissions /> },
    ]
  },
  {
    path: "/login",
    element: <Login />
  }
])
export default routes
