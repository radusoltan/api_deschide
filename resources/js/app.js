import { createRoot } from 'react-dom/client'
import {RouterProvider} from "react-router-dom"
import {Provider} from "react-redux"
import routes from "./routes"
import store from "./store"

// Render your React component instead
const root = createRoot(document.getElementById('root'));
root.render(
  <Provider store={store}>
    <RouterProvider router={routes}/>
  </Provider>
);
