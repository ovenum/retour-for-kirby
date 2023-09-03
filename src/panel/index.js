// Views
import RedirectsView from "./components/Views/RedirectsView.vue";
import FailuresView from "./components/Views/FailuresView.vue";
import SystemView from "./components/Views/SystemView.vue";

// Components
import Stats from "./components/Stats/Stats.vue";
import Tabs from "./components/Navigation/Tabs.vue";
import Timespan from "./components/Navigation/Timespan.vue";

// Table previews
import CountFieldPreview from "./components/Table/CountFieldPreview.vue";
import PathFieldPreview from "./components/Table/PathFieldPreview.vue";
import PriorityFieldPreview from "./components/Table/PriorityFieldPreview.vue";
import StatusFieldPreview from "./components/Table/StatusFieldPreview.vue";

// Fields
import StatusField from "./components/Fields/StatusField.vue";

panel.plugin("distantnative/retour", {
  components: {
    "k-count-field-preview": CountFieldPreview,
    "k-path-field-preview": PathFieldPreview,
    "k-priority-field-preview": PriorityFieldPreview,
    "k-status-field-preview": StatusFieldPreview,

    "k-retour-stats": Stats,
    "k-retour-tabs": Tabs,
    "k-retour-timespan": Timespan,

    "k-retour-redirects-view": RedirectsView,
    "k-retour-failures-view": FailuresView,
    "k-retour-system-view": SystemView,
  },
  fields: {
    "retour-status": StatusField,
  },
  icons: {
    "retour-circle-focus":
      '<path d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z"></path>',
  },
});
