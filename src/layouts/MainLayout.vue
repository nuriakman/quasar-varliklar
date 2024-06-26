<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          Quasar ile Varlık Hesabı v{{ $q.version }}</q-toolbar-title
        >
        <q-space />

        <q-btn
          flat
          dense
          rounded
          @click="$q.fullscreen.toggle()"
          :icon="$q.fullscreen.isActive ? 'fullscreen_exit' : 'fullscreen'"
          :xlabel="$q.fullscreen.isActive ? 'TamEkran:Kapat' : 'TamEkran:Aç'"
          :no-caps="true"
          class="q-mr-sm"
        />

        <q-btn
          flat
          dense
          rounded
          @click="$q.dark.toggle()"
          :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
          :xlabel="$q.fullscreen.isActive ? 'Dark' : 'Light'"
          class="q-mr-sm"
        />

        <template v-for="item in topMenuItems" :key="item.page_slug">
          <q-separator dark vertical />

          <q-btn-dropdown stretch no-caps glossy flat :label="item.page_name">
            <q-list>
              <q-item
                v-for="subItem in item.sub_menu"
                :key="`x.${subItem.page_slug}`"
                clickable
                outline
                v-close-popup
                tabindex="0"
                bordered
                :to="'/page/' + item.page_slug"
              >
                <q-item-section>
                  <q-item-label>
                    <q-icon name="chevron_right" color="dark" size="md" />
                    {{ subItem.page_name }}</q-item-label
                  >
                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </template>

        <q-separator dark vertical />
        <q-btn
          flat
          round
          dense
          icon="apps"
          size="lg"
          class="q-mr-xs"
          @click="showMenu = !showMenu"
        />
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-scroll-area class="fit">
        <q-list>
          <q-item-label header>Yapabileceğiniz İşlemler </q-item-label>
          <EssentialLink
            v-for="item in leftDrawerItems"
            :key="item.title"
            v-bind="item"
          />
        </q-list>

        <hr class="q-my-lg" />

        <q-list>
          <q-item-label header>Menü İçerikleri</q-item-label>
          <q-expansion-item
            icon="star"
            :label="page.page_name"
            v-for="page in getParents()"
            :key="page.id"
            @click="toggleSubMenu(page)"
          >
            <q-card class="q-ml-md" v-if="page.showSubMenu">
              <q-list bordered separator>
                <q-item
                  clickable
                  v-for="subPage in getChildren(page.id)"
                  :key="subPage.id"
                >
                  <q-item-section @click.prevent="navigate(subPage)">
                    &nbsp;&nbsp;{{ subPage.page_name }}
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card>
          </q-expansion-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import EssentialLink, {
  EssentialLinkProps,
} from 'src/components/EssentialLink.vue';

const leftDrawerItems: EssentialLinkProps[] = [
  {
    title: 'Nereden Nereye Seçimi',
    caption: 'Nereden Nereye',
    icon: 'home',
    link: '/',
  },
  {
    title: 'Tarih seçimi',
    caption: 'Tarih',
    icon: 'work',
    link: '/assets',
  },
  {
    title: 'Tatiller',
    caption: 'Tatiller',
    icon: 'calendar_month',
    link: '/about',
  },
  {
    title: 'Tanımlar',
    caption: 'Varlık türleri',
    icon: 'settings',
    link: '/asset-types',
  },
  {
    title: 'Test',
    caption: 'Test',
    icon: 'mms',
    link: '/test',
  },
  {
    title: 'Test 1',
    caption: 'Test 1',
    icon: 'chat_bubble',
    link: '/test1',
  },
  {
    title: 'Test 2',
    caption: 'Test 2',
    icon: 'food_bank',
    link: '/test2',
  },
  {
    title: 'Test 3',
    caption: 'Test 3',
    icon: 'comment_bank',
    link: '/test3',
  },
];

const leftDrawerOpen = ref(false);
const showMenu = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

const topMenuItems = [
  {
    page_name: 'Kurumsal',
    page_slug: 'kurumsal',
    sub_menu: [
      {
        page_name: 'İlkelerimiz',
        page_slug: 'ilkelerimiz',
      },
      {
        page_name: 'Vizyonumuz',
        page_slug: 'vizyonumuz',
      },
      {
        page_name: 'Misyonumuz',
        page_slug: 'misyonumuz',
      },
      {
        page_name: 'Kalite Politikamız',
        page_slug: 'kalite-politikamiz',
      },
      {
        page_name: 'Belgelerimiz',
        page_slug: 'belgelerimiz',
      },
      {
        page_name: 'Filomuz',
        page_slug: 'filomuz',
      },
      {
        page_name: 'Gizlilik Bildirimimiz',
        page_slug: 'gizlilik-bildirimimiz',
      },
      {
        page_name: 'İnsan Kaynakları',
        page_slug: 'insan-kaynaklari',
      },
    ],
  },

  {
    page_name: 'Seyahat',
    page_slug: 'seyahat',
    sub_menu: [
      { page_name: 'Bagaj İşlemleri', page_slug: 'bagaj-islemleri' },
      {
        page_name: 'Yolcu Taşıma Sözleşmesi',
        page_slug: 'yolcu-tasima-sozlesmesi',
      },
      {
        page_name: 'Sıkça Sorulan Sorular',
        page_slug: 'sikca-sorulan-sorular',
      },
      { page_name: 'Neden Özlem Seyahat?', page_slug: 'neden-ozlem-seyahat' },
      { page_name: '444 18 18', page_slug: '444-18-18' },
      { page_name: 'Peronlar', page_slug: 'peronlar' },
      { page_name: 'Zaman Tarifesi', page_slug: 'zaman-tarifesi' },
      { page_name: 'Ücret Tarifesi', page_slug: 'ucret-tarifesi' },
      { page_name: 'Açık Bilet', page_slug: 'acik-bilet' },
    ],
  },

  {
    page_name: 'İletişim',
    page_slug: 'iletisim',
    sub_menu: [
      { page_name: 'Telefonlar / Şubeler', page_slug: 'telefonlar-subeler' },
      { page_name: 'İletişim', page_slug: 'iletisim' },
      { page_name: 'e-Bilet', page_slug: 'e-bilet' },
    ],
  },
];

// MENU
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const pages = ref<IPage[]>([]);
const router = useRouter();

interface IPage {
  id: number;
  page_name: string;
  page_slug: string;
  parent_id: number;
  showSubMenu: boolean;
}

onMounted(() => {
  fetchPages();
});

async function fetchPages() {
  try {
    const response = await axios.get(
      'http://localhost/quasar/varliklar/public/api/menu.php'
    );
    pages.value = response.data.map((page: Omit<IPage, 'showSubMenu'>) => ({
      ...page,
      showSubMenu: false, // Gelen içerikteki her bir elemana "showSubMenu: false" özelliği ekleyelim
    }));
  } catch (error) {
    console.error('Error fetching pages:', error);
  }
}

function toggleSubMenu(page: IPage) {
  page.showSubMenu = !page.showSubMenu;
}

function navigate(page: IPage) {
  router.push({ path: '/bilgi/' + page.page_slug });
}

function getChildren(parentId: number): IPage[] {
  return pages.value.filter((page) => page.parent_id == parentId);
}

function getParents(): IPage[] {
  return pages.value.filter((page) => page.parent_id == 0);
}
</script>
