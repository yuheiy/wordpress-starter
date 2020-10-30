import path from "path";
import { Definition } from "@stimulus/core";

interface ECMAScriptModule {
  __esModule: boolean;
  default?: object;
}

export function definitionsFromContext(
  context: __WebpackModuleApi.RequireContext
): Definition[] {
  return context
    .keys()
    .map((key) => definitionForModuleWithContextAndKey(context, key))
    .filter((value) => value) as Definition[];
}

function definitionForModuleWithContextAndKey(
  context: __WebpackModuleApi.RequireContext,
  key: string
): Definition | undefined {
  const identifier = identifierForContextKey(key);
  return definitionForModuleAndIdentifier(context(key), identifier);
}

function definitionForModuleAndIdentifier(
  module: ECMAScriptModule,
  identifier: string
): Definition | undefined {
  const controllerConstructor = module.default as any;
  if (typeof controllerConstructor !== "function") {
    return;
  }
  return { identifier, controllerConstructor };
}

function identifierForContextKey(key: string) {
  return path.basename(key, path.extname(key));
}
