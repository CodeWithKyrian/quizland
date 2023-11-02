declare namespace App.Data {
export type CategoryData = {
name: string;
slug: string;
posts?: Array<App.Data.PostData>;
posts_count?: number;
};
export type PostData = {
title: string;
slug: string;
description: string;
body: string;
category: App.Data.CategoryData;
published_at: string | null;
};
}
