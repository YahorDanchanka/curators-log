export interface MenuItem {
  label: string
  icon?: string
  download?: boolean
  hidden?: boolean
}

export type MenuItemWithRoute = MenuItem & { route: string }
export type MenuItemWithHandler = MenuItem & { onClick: (item: MenuItemWithHandler) => void }
export type MenuItemWithSubmenu = MenuItem & { items: MenuList }
export type MenuList = (MenuItemWithRoute | MenuItemWithHandler | MenuItemWithSubmenu)[]

export function isMenuItemWithRoute(object: object): object is MenuItemWithRoute {
  return 'route' in object
}

export function isMenuItemWithHandler(object: object): object is MenuItemWithHandler {
  return !isMenuItemWithRoute(object)
}
