<?php

namespace App\Filament\Arya\Resources\FinancialHealthScores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\deleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\FinancialHealthScore;
use App\Services\FinancialHealthService;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class FinancialHealthScoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workspace.name')->label('Name Workspace')->sortable()->searchable(),
                TextColumn::make('score')->label('Financial Health Score')->badge()->suffix('%')
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->label('Calculation Time')->dateTime('d M Y H:i'),
                
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                deleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->label('Hapus Data Terpilih')
                    ->modalHeading('Hapus Goals Terpilih') // Judul di dalam modal
                    ->modalDescription('Apakah Anda yakin ingin menghapus semua data yang dicentang? Tindakan ini tidak dapat dibatalkan.')  // Teks deskripsi modal
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang'),

                ])
                ->Label('Aksi'),

                
            ])
            ->headerActions([
                Action::make('hitungSkor')
                    ->label('Jalankan Analisis Baru')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Select::make('workspace_id')
                            ->label('Pilih target workspace')
                            ->relationship('workspace', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                    ])
                    ->action(function (array $data, FinancialHealthService $service) {
                        $service->hitungDanSimpanSkor($data['workspace_id']);

                        Notification::make()
                            ->title('Kalkulasi selesai')
                            ->success()
                            ->send();
                    }),
            ]);

    }


}
