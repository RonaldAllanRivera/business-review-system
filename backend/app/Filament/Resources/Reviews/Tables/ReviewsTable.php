<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business.name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Review::STATUS_PENDING => 'Pending',
                        Review::STATUS_APPROVED => 'Approved',
                        Review::STATUS_REJECTED => 'Rejected',
                    ]),
                SelectFilter::make('business_id')
                    ->label('Business')
                    ->relationship('business', 'name')
                    ->searchable(),
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Review $record) => auth()->user()?->can('review.moderate') && $record->status !== Review::STATUS_APPROVED)
                    ->requiresConfirmation()
                    ->action(function (Review $record) {
                        $record->update([
                            'status' => Review::STATUS_APPROVED,
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                            'rejection_reason' => null,
                        ]);
                        Cache::forget('businesses:show:' . $record->business_id);
                        $ver = (int) Cache::get('businesses:index:version', 1);
                        Cache::forever('businesses:index:version', $ver + 1);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Review $record) => auth()->user()?->can('review.moderate') && $record->status !== Review::STATUS_REJECTED)
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (array $data, Review $record) {
                        $record->update([
                            'status' => Review::STATUS_REJECTED,
                            'moderated_by' => auth()->id(),
                            'moderated_at' => now(),
                            'rejection_reason' => $data['reason'],
                        ]);
                        Cache::forget('businesses:show:' . $record->business_id);
                        $ver = (int) Cache::get('businesses:index:version', 1);
                        Cache::forever('businesses:index:version', $ver + 1);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                /** @var Review $record */
                                $record->update([
                                    'status' => Review::STATUS_APPROVED,
                                    'moderated_by' => auth()->id(),
                                    'moderated_at' => now(),
                                    'rejection_reason' => null,
                                ]);
                                Cache::forget('businesses:show:' . $record->business_id);
                            }
                            $ver = (int) Cache::get('businesses:index:version', 1);
                            Cache::forever('businesses:index:version', $ver + 1);
                        }),
                    BulkAction::make('bulk_reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->form([
                            Textarea::make('reason')
                                ->label('Reason')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                /** @var Review $record */
                                $record->update([
                                    'status' => Review::STATUS_REJECTED,
                                    'moderated_by' => auth()->id(),
                                    'moderated_at' => now(),
                                    'rejection_reason' => $data['reason'],
                                ]);
                                Cache::forget('businesses:show:' . $record->business_id);
                            }
                            $ver = (int) Cache::get('businesses:index:version', 1);
                            Cache::forever('businesses:index:version', $ver + 1);
                        }),
                ]),
            ]);
    }
}
