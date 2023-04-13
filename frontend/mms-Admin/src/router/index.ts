import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard/Dashboard.vue";
import dashboardLayout from "@/layouts/dashboardLayout.vue";
import settingsLayout from "@/layouts/settingsLayout.vue";
import {
  Mentors,
  Profile,
  Programs,
  Reports,
  Tasks,
  MentorManagers,
  Certificates,
  ApprovalRequests,
  Messages,
  Settings,
  DiscussionForum,
} from "@/views/Admin";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior() {
    return { top: 0, behavior: "smooth" };
  },
  routes: [
    {
      path: "/",
      name: "home",
      redirect: "/admin/dashboard",
    },
    {
      path: "/admin",
      name: "dashboardLayout",
      component: dashboardLayout,
      redirect: "/admin/dashboard",
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          component: Dashboard,
        },
        {
          path: "profile",
          name: "profile",
          component: Profile,
        },
        {
          path: "programs",
          name: "programs",
          component: Programs,
        },
        {
          path: "tasks",
          name: "tasks",
          component: Tasks,
        },
        {
          path: "reports",
          name: "reports",
          component: Reports,
        },
        {
          path: "mentors",
          name: "mentors",
          component: Mentors,
        },
        {
          path: "mentor-managers",
          name: "mentor-managers",
          component: MentorManagers,
        },
        {
          path: "approval-requests",
          name: "approval-requests",
          component: ApprovalRequests,
        },
        {
          path: "certificates",
          name: "certificates",
          component: Certificates,
        },
        {
          path: "messages",
          name: "messages",
          component: Messages,
        },
        {
          path: "discussion-forum",
          component: DiscussionForum,
        },
        {
          path: "settings",
          name: "settings",
          component: Settings,
        },
        {
          path: "settings",
          name: "settings",
          component: settingsLayout,
          redirect: "general",
          children: [
            {
              path: "general",
              name: "general",
              component: () => import("@/views/Settings/General.vue"),
            },
            {
              path: "notifications",
              name: "notifications",
              component: () => import("@/views/Settings/Notifications.vue"),
            },
            {
              path: "privacy",
              name: "privacy",
              component: () => import("@/views/Settings/Privacy.vue"),
            },
            {
              path: "archive",
              name: "archive",
              component: () => import("@/views/Settings/Archive.vue"),
            },
            {
              path: "password",
              name: "password",
              component: () => import("@/views/Settings/Password.vue"),
            },
            {
              path: "FAQ",
              name: "FAQ",
              component: () => import("@/views/Settings/FAQ.vue"),
            },
            {
              path: "support",
              name: "support",
              component: () => import("@/views/Settings/Support.vue"),
            },
          ],
        },
      ],
    },
    {
      path: "/login",
      name: "loginLayout",
      component: () => import("@/layouts/authLayout.vue"),
      children: [
        {
          path: "/login",
          name: "login",
          component: () => import("@/views/Auth/Login.vue"),
        },
        {
          path: "/reset-password",
          name: "reset-password",
          component: () => import("@/views/Auth/ResetPassword.vue"),
        },
        {
          path: "/change-password",
          name: "change-password",
          component: () => import("@/views/Auth/ChangePassword.vue"),
        },
        {
          path: "/confirm-reset",
          name: "confirm-reset",
          component: () => import("@/views/Auth/AcknowledgePasswordReset.vue"),
        },
      ],
    },
  ],
});

export default router;
