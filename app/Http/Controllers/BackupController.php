<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
        $this->middleware('auth');
        $this->middleware('role:admin,lembaga');
    }

    /**
     * Display backup dashboard
     */
    public function dashboard()
    {
        $stats = $this->backupService->getBackupStats();
        $backups = $this->backupService->getBackupList();
        $logs = $this->backupService->getBackupLogs(20);
        $nextBackup = $this->backupService->getNextBackupTime();

        return view('backup.dashboard', compact('stats', 'backups', 'logs', 'nextBackup'));
    }

    /**
     * Create manual backup
     */
    public function createBackup(Request $request)
    {
        $type = $request->get('type', 'daily');
        $result = $this->backupService->createBackup($type);

        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', 'Backup created successfully: ' . $result['file_name']);
        }

        return redirect()->back()->with('error', 'Backup failed: ' . $result['message']);
    }

    /**
     * Restore from backup
     */
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'confirm' => 'required|accepted'
        ]);

        $result = $this->backupService->restoreBackup($request->file_name);

        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', 'Database restored successfully');
        }

        return redirect()->back()->with('error', 'Restore failed: ' . $result['message']);
    }

    /**
     * Delete backup
     */
    public function deleteBackup(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string'
        ]);

        $result = $this->backupService->deleteBackup($request->file_name);

        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', 'Backup deleted successfully');
        }

        return redirect()->back()->with('error', 'Delete failed: ' . $result['message']);
    }

    /**
     * Download backup file
     */
    public function downloadBackup($fileName)
    {
        $filePath = $this->backupService->getBackupPath() . '/' . $fileName;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file not found');
        }

        return response()->download($filePath, $fileName);
    }

    /**
     * Test backup integrity
     */
    public function testBackup(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string'
        ]);

        $result = $this->backupService->testBackupIntegrity($request->file_name);

        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', 'Backup integrity test passed');
        }

        return redirect()->back()->with('error', 'Integrity test failed: ' . $result['message']);
    }

    /**
     * Get backup statistics API
     */
    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->backupService->getBackupStats()
        ]);
    }

    /**
     * Get backup list API
     */
    public function list()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->backupService->getBackupList()
        ]);
    }
}